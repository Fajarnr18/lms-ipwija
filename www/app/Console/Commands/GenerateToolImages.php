<?php

namespace App\Console\Commands;

use App\Models\Tool;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GenerateToolImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-tool-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate and save placeholder images for all tools';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tools = Tool::all();
        $this->info("Generating images for " . $tools->count() . " tools...");

        $colors = ['#EF4444', '#F97316', '#F59E0B', '#10B981', '#3B82F6', '#6366F1', '#8B5CF6', '#EC4899'];

        if (!Storage::disk('public')->exists('alat')) {
            Storage::disk('public')->makeDirectory('alat');
        }

        $bar = $this->output->createProgressBar(count($tools));

        foreach ($tools as $tool) {
            $initials = strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $tool->nama_alat), 0, 2));
            if (empty($initials)) $initials = 'T';

            $color = $colors[crc32($tool->nama_alat) % count($colors)];
            
            // Create a 512x512 image using GD
            $img = imagecreatetruecolor(512, 512);
            
            // Hex to RGB
            list($r, $g, $b) = sscanf($color, "#%02x%02x%02x");
            $bgColor = imagecolorallocate($img, $r, $g, $b);
            $textColor = imagecolorallocate($img, 255, 255, 255);
            
            imagefill($img, 0, 0, $bgColor);
            
            // Draw text (we use built-in font for simplicity, scaled up)
            // Or better, since built-in fonts are small, let's just make it a colored square
            // We can add some basic lines to make it look like an icon
            $font = 5; // Built-in font 1-5
            $fw = imagefontwidth($font);
            $fh = imagefontheight($font);
            $text = $initials;
            // Draw it multiple times to simulate large text or just draw it centered
            // Since GD built in fonts are tiny, we can use imagecopyresized to scale it
            
            $textImg = imagecreatetruecolor($fw * strlen($text), $fh);
            $bgT = imagecolorallocate($textImg, $r, $g, $b);
            $fgT = imagecolorallocate($textImg, 255, 255, 255);
            imagefill($textImg, 0, 0, $bgT);
            imagestring($textImg, $font, 0, 0, $text, $fgT);
            
            // Scale the text image 20x
            imagecopyresized($img, $textImg, (512 - ($fw * strlen($text) * 20)) / 2, (512 - ($fh * 20)) / 2, 0, 0, $fw * strlen($text) * 20, $fh * 20, $fw * strlen($text), $fh);
            imagedestroy($textImg);

            $filename = 'alat/' . Str::random(40) . '.png';
            $path = Storage::disk('public')->path($filename);
            
            imagepng($img, $path);
            imagedestroy($img);

            // Update DB
            $tool->foto_alat = $filename;
            $tool->save();

            $bar->advance();
        }

        $bar->finish();
        $this->info("\nImages successfully generated and saved to storage/app/public/alat!");
    }
}
