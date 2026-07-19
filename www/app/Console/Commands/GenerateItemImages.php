<?php

namespace App\Console\Commands;

use App\Models\Item;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GenerateItemImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-item-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate and save placeholder images for all items in inventaris';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $items = Item::all();
        $this->info("Generating images for " . $items->count() . " items...");

        $colors = ['#EF4444', '#F97316', '#F59E0B', '#10B981', '#3B82F6', '#6366F1', '#8B5CF6', '#EC4899'];

        if (!Storage::disk('public')->exists('inventaris')) {
            Storage::disk('public')->makeDirectory('inventaris');
        }

        $bar = $this->output->createProgressBar(count($items));

        foreach ($items as $item) {
            $initials = strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $item->nama_barang), 0, 2));
            if (empty($initials)) $initials = 'IT';

            $color = $colors[crc32($item->nama_barang) % count($colors)];
            
            $img = imagecreatetruecolor(512, 512);
            list($r, $g, $b) = sscanf($color, "#%02x%02x%02x");
            $bgColor = imagecolorallocate($img, $r, $g, $b);
            imagefill($img, 0, 0, $bgColor);
            
            $font = 5; 
            $fw = imagefontwidth($font);
            $fh = imagefontheight($font);
            $text = $initials;
            
            $textImg = imagecreatetruecolor($fw * strlen($text), $fh);
            $bgT = imagecolorallocate($textImg, $r, $g, $b);
            $fgT = imagecolorallocate($textImg, 255, 255, 255);
            imagefill($textImg, 0, 0, $bgT);
            imagestring($textImg, $font, 0, 0, $text, $fgT);
            
            imagecopyresized($img, $textImg, (512 - ($fw * strlen($text) * 20)) / 2, (512 - ($fh * 20)) / 2, 0, 0, $fw * strlen($text) * 20, $fh * 20, $fw * strlen($text), $fh);
            imagedestroy($textImg);

            $filename = 'inventaris/' . Str::random(40) . '.png';
            $path = Storage::disk('public')->path($filename);
            
            imagepng($img, $path);
            imagedestroy($img);

            $item->foto_barang = $filename;
            $item->save();

            $bar->advance();
        }

        $bar->finish();
        $this->info("\nImages successfully generated and saved to storage/app/public/inventaris!");
    }
}
