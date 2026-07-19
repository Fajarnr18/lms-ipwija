import React, { useState } from 'react';
import {
  Search, ChevronDown, Download, Printer, ClipboardList, AlertTriangle,
  Clock, Users, Filter, MoreVertical, LogOut, LayoutDashboard, Wrench,
  Package, BookOpen, RotateCcw, FileText, Shield, UserCog,
  ChevronLeft, ChevronRight, Calendar
} from 'lucide-react';

const menuItems = [
  { icon: LayoutDashboard, label: 'Dashboard' },
  { icon: Wrench, label: 'Manajemen Alat' },
  { icon: Package, label: 'Inventaris Barang' },
  { icon: BookOpen, label: 'Peminjaman' },
  { icon: RotateCcw, label: 'Pengembalian' },
  { icon: FileText, label: 'Laporan', active: true },
  { icon: Shield, label: 'Audit Trail' },
  { icon: UserCog, label: 'Manajemen User' },
];

const stats = [
  { value: '124', label: 'Total Pinjaman Aktif', color: '#3B82F6', icon: ClipboardList },
  { value: '12', label: 'Terlambat Kembali', color: '#EF4444', icon: AlertTriangle },
  { value: '45', label: 'Kembali Hari Ini', color: '#F59E0B', icon: Clock },
  { value: '89', label: 'Peminjam Unik', color: '#8B5CF6', icon: Users },
];

const transactions = [
  {
    id: 'PMJ-2023-0891', peminjam: 'Andi Hermawan', nim: '201011400234', role: 'Mahasiswa',
    alat: 'Smart Car Acrylic Chassis', tglPinjam: '20 Okt 2023', estimasi: '22 Okt 2023', status: 'TERLAMBAT',
  },
  {
    id: 'PMJ-2023-0895', peminjam: 'Siti Aminah', nim: '201011456611', role: 'Mahasiswa',
    alat: 'Motor servo (SG900)', tglPinjam: '21 Okt 2023', estimasi: '24 Okt 2023', status: 'DIPINJAM',
  },
  {
    id: 'PMJ-2023-0899', peminjam: 'Budi Santoso', nim: '1234567890', role: 'Dosen',
    alat: 'Wheels', tglPinjam: '22 Okt 2023', estimasi: '25 Okt 2023', status: 'DIPINJAM',
  },
  {
    id: 'PMJ-2023-0888', peminjam: 'Rina Melati', nim: '201011400999', role: 'Mahasiswa',
    alat: 'sensor shield v5.0', tglPinjam: '18 Okt 2023', estimasi: '20 Okt 2023', status: 'TERLAMBAT',
  },
];

export default function LaporanAlatDipinjam() {
  const [statusFilter, setStatusFilter] = useState('Semua Status');

  const statusBadge = (status) => {
    const isLate = status === 'TERLAMBAT';
    return (
      <span style={{
        display: 'inline-flex', alignItems: 'center', padding: '4px 12px', borderRadius: 9999,
        fontSize: 11, fontWeight: 600, letterSpacing: '.03em',
        background: isLate ? '#FEF2F2' : '#F5F3FF', color: isLate ? '#DC2626' : '#7C3AED',
      }}>
        <span style={{
          width: 6, height: 6, borderRadius: '50%', marginRight: 5,
          background: isLate ? '#DC2626' : '#7C3AED',
        }} />
        {status}
      </span>
    );
  };

  return (
    <div style={{ display: 'flex', minHeight: '100vh', fontFamily: "'Inter', system-ui, sans-serif", background: '#F5F6FA' }}>
      {/* SIDEBAR */}
      <aside style={{
        width: 240, flexShrink: 0, background: '#0F1B3D', color: '#fff',
        display: 'flex', flexDirection: 'column', position: 'fixed', top: 0, left: 0, height: '100vh', zIndex: 40,
      }}>
        <div style={{ padding: '24px 20px 20px', borderBottom: '1px solid rgba(255,255,255,.07)' }}>
          <div style={{ fontSize: 18, fontWeight: 800, letterSpacing: '-.03em' }}>IPWIJA Lab</div>
          <div style={{ fontSize: 10, color: 'rgba(255,255,255,.35)', textTransform: 'uppercase', letterSpacing: '.12em', marginTop: 2 }}>ADMIN PORTAL</div>
        </div>
        <nav style={{ flex: 1, padding: '12px 12px', display: 'flex', flexDirection: 'column', gap: 1 }}>
          {menuItems.map((item) => (
            <a key={item.label} href="#" style={{
              display: 'flex', alignItems: 'center', gap: 10, padding: '10px 14px',
              borderRadius: 10, fontSize: 13, fontWeight: 500, textDecoration: 'none',
              background: item.active ? 'rgba(59,130,246,.2)' : 'transparent',
              color: item.active ? '#fff' : 'rgba(255,255,255,.5)',
              transition: 'all .15s',
            }}>
              <item.icon size={18} />
              {item.label}
            </a>
          ))}
        </nav>
        <div style={{ padding: '14px 12px', borderTop: '1px solid rgba(255,255,255,.07)' }}>
          <button style={{
            display: 'flex', alignItems: 'center', gap: 10, padding: '10px 16px',
            width: '100%', border: '1px solid rgba(239,68,68,.3)', borderRadius: 10,
            fontSize: 13, fontWeight: 600, color: '#FCA5A5', background: 'rgba(239,68,68,.08)',
            cursor: 'pointer', fontFamily: 'inherit', transition: 'all .15s',
          }}>
            <LogOut size={18} /> Logout
          </button>
        </div>
      </aside>

      {/* MAIN */}
      <div style={{ marginLeft: 240, flex: 1, display: 'flex', flexDirection: 'column', minHeight: '100vh' }}>
        {/* TOPBAR */}
        <header style={{
          background: '#fff', borderBottom: '1px solid #E5E7EB', height: 64,
          display: 'flex', alignItems: 'center', padding: '0 28px', position: 'sticky', top: 0, zIndex: 30,
        }}>
          <div style={{ position: 'relative', flex: 1, maxWidth: 380 }}>
            <Search size={16} style={{ position: 'absolute', left: 12, top: '50%', transform: 'translateY(-50%)', color: '#9CA3AF' }} />
            <input placeholder="Cari laporan atau ID..." style={{
              width: '100%', padding: '8px 12px 8px 36px', border: '1px solid #E5E7EB',
              borderRadius: 8, fontSize: 13, fontFamily: 'inherit', background: '#F9FAFB',
              outline: 'none',
            }} />
          </div>
          <div style={{ display: 'flex', alignItems: 'center', gap: 12, marginLeft: 'auto' }}>
            <div style={{
              width: 34, height: 34, borderRadius: '50%',
              background: 'linear-gradient(135deg,#1E3A5F,#3B82F6)',
              display: 'flex', alignItems: 'center', justifyContent: 'center',
              color: '#fff', fontWeight: 700, fontSize: 13,
            }}>A</div>
            <div>
              <div style={{ fontSize: 13, fontWeight: 600, color: '#1A1A2E', lineHeight: 1.2 }}>Admin Lab</div>
              <div style={{ fontSize: 11, color: '#6B7280', lineHeight: 1.2, marginTop: 1 }}>Administrator</div>
            </div>
          </div>
        </header>

        {/* CONTENT */}
        <main style={{ padding: '28px', maxWidth: 1280, width: '100%', margin: '0 auto' }}>
          {/* HEADER */}
          <div style={{
            display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start',
            flexWrap: 'wrap', gap: 12, marginBottom: 24,
          }}>
            <div>
              <h1 style={{ fontSize: 24, fontWeight: 700, color: '#1A1A2E', margin: 0 }}>Alat Sedang Dipinjam</h1>
              <p style={{ fontSize: 13, color: '#6B7280', margin: '4px 0 0' }}>
                Laporan real-time penggunaan peralatan laboratorium Universitas IPWIJA.
              </p>
            </div>
            <div style={{ display: 'flex', gap: 8 }}>
              <button style={{
                display: 'inline-flex', alignItems: 'center', gap: 6,
                padding: '9px 16px', borderRadius: 8, fontSize: 13, fontWeight: 500,
                border: '1.5px solid #D1D5DB', background: '#fff', color: '#374151',
                cursor: 'pointer', fontFamily: 'inherit',
              }}>
                <Download size={15} /> Export Excel
              </button>
              <button style={{
                display: 'inline-flex', alignItems: 'center', gap: 6,
                padding: '9px 16px', borderRadius: 8, fontSize: 13, fontWeight: 500,
                border: 'none', background: '#2563EB', color: '#fff',
                cursor: 'pointer', fontFamily: 'inherit',
              }}>
                <Printer size={15} /> Cetak Laporan
              </button>
            </div>
          </div>

          {/* FILTER */}
          <div style={{
            background: '#fff', border: '1px solid #E5E7EB', borderRadius: 12,
            padding: '16px 20px', marginBottom: 24,
            display: 'flex', gap: 16, alignItems: 'flex-end', flexWrap: 'wrap',
          }}>
            <div style={{ display: 'flex', flexDirection: 'column', gap: 4 }}>
              <label style={{ fontSize: 11, fontWeight: 600, color: '#374151' }}>PILIH JENIS LAPORAN</label>
              <div style={{
                display: 'flex', alignItems: 'center', gap: 6,
                padding: '8px 12px', border: '1.5px solid #E5E7EB', borderRadius: 8,
                fontSize: 13, minWidth: 190, cursor: 'pointer',
              }}>
                <span style={{ flex: 1 }}>Alat Sedang Dipinjam</span>
                <ChevronDown size={14} color="#6B7280" />
              </div>
            </div>
            <div style={{ display: 'flex', flexDirection: 'column', gap: 4 }}>
              <label style={{ fontSize: 11, fontWeight: 600, color: '#374151' }}>START DATE</label>
              <div style={{
                display: 'flex', alignItems: 'center', gap: 6,
                padding: '8px 12px', border: '1.5px solid #E5E7EB', borderRadius: 8, fontSize: 13,
              }}>
                <Calendar size={14} color="#9CA3AF" />
                <span style={{ color: '#9CA3AF' }}>mm/dd/yyyy</span>
              </div>
            </div>
            <div style={{ display: 'flex', flexDirection: 'column', gap: 4 }}>
              <label style={{ fontSize: 11, fontWeight: 600, color: '#374151' }}>END DATE</label>
              <div style={{
                display: 'flex', alignItems: 'center', gap: 6,
                padding: '8px 12px', border: '1.5px solid #E5E7EB', borderRadius: 8, fontSize: 13,
              }}>
                <Calendar size={14} color="#9CA3AF" />
                <span style={{ color: '#9CA3AF' }}>mm/dd/yyyy</span>
              </div>
            </div>
          </div>

          {/* STATS */}
          <div style={{
            display: 'grid', gridTemplateColumns: 'repeat(4, 1fr)', gap: 16, marginBottom: 24,
          }}>
            {stats.map((s) => (
              <div key={s.label} style={{
                background: '#fff', border: '1px solid #E5E7EB', borderRadius: 12,
                padding: '18px 20px', boxShadow: '0 1px 2px rgba(0,0,0,.04)',
              }}>
                <div style={{
                  width: 40, height: 40, borderRadius: 10,
                  background: `${s.color}15`, display: 'flex',
                  alignItems: 'center', justifyContent: 'center', marginBottom: 12,
                }}>
                  <s.icon size={18} color={s.color} />
                </div>
                <div style={{ fontSize: 22, fontWeight: 700, color: s.label === 'Terlambat Kembali' ? '#DC2626' : '#1A1A2E' }}>
                  {s.value}
                </div>
                <div style={{ fontSize: 12, color: '#6B7280', fontWeight: 500, marginTop: 2 }}>{s.label}</div>
              </div>
            ))}
          </div>

          {/* TABLE */}
          <div style={{
            background: '#fff', border: '1px solid #E5E7EB', borderRadius: 12,
            overflow: 'hidden', boxShadow: '0 1px 2px rgba(0,0,0,.04)',
          }}>
            <div style={{
              display: 'flex', alignItems: 'center', justifyContent: 'space-between',
              padding: '16px 20px', borderBottom: '1px solid #E5E7EB',
            }}>
              <div style={{ display: 'flex', alignItems: 'center', gap: 8 }}>
                <span style={{ fontSize: 15, fontWeight: 700, color: '#1A1A2E' }}>Data Transaksi</span>
                <span style={{
                  display: 'inline-flex', alignItems: 'center', gap: 5,
                  padding: '3px 10px', borderRadius: 9999, fontSize: 11, fontWeight: 600,
                  background: '#ECFDF5', color: '#059669',
                }}>
                  <span style={{ width: 6, height: 6, borderRadius: '50%', background: '#059669' }} />
                  Live Update
                </span>
              </div>
              <div style={{ display: 'flex', gap: 8, alignItems: 'center' }}>
                <div style={{
                  display: 'flex', alignItems: 'center', gap: 6,
                  padding: '6px 12px', border: '1.5px solid #E5E7EB', borderRadius: 8,
                  fontSize: 12, fontWeight: 500, color: '#374151', cursor: 'pointer',
                }}>
                  {statusFilter}
                  <ChevronDown size={13} />
                </div>
                <Filter size={16} color="#6B7280" style={{ cursor: 'pointer' }} />
              </div>
            </div>

            <div style={{ overflowX: 'auto' }}>
              <table style={{ width: '100%', borderCollapse: 'collapse', fontSize: 13 }}>
                <thead>
                  <tr style={{ borderBottom: '1px solid #F3F4F6' }}>
                    {['ID PINJAM', 'PEMINJAM', 'NAMA ALAT', 'TGL PINJAM', 'ESTIMASI KEMBALI', 'STATUS', 'AKSI'].map((h) => (
                      <th key={h} style={{
                        textAlign: 'left', padding: '10px 16px', fontWeight: 600,
                        color: '#6B7280', fontSize: 11, textTransform: 'uppercase',
                        letterSpacing: '.04em', whiteSpace: 'nowrap',
                      }}>{h}</th>
                    ))}
                  </tr>
                </thead>
                <tbody>
                  {transactions.map((row, i) => (
                    <tr key={row.id} style={{ borderBottom: '1px solid #F3F4F6' }}>
                      <td style={{ padding: '12px 16px', fontWeight: 600, color: '#1E3A5F', fontSize: 12, whiteSpace: 'nowrap' }}>
                        {row.id}
                      </td>
                      <td style={{ padding: '12px 16px' }}>
                        <div style={{ fontWeight: 600, color: '#1A1A2E', fontSize: 13 }}>{row.peminjam}</div>
                        <div style={{ fontSize: 11, color: '#9CA3AF', marginTop: 1 }}>
                          {row.nim} &mdash; {row.role}
                        </div>
                      </td>
                      <td style={{ padding: '12px 16px', color: '#6B7280', fontSize: 12 }}>{row.alat}</td>
                      <td style={{ padding: '12px 16px', color: '#6B7280', fontSize: 12, whiteSpace: 'nowrap' }}>{row.tglPinjam}</td>
                      <td style={{
                        padding: '12px 16px', fontSize: 12, whiteSpace: 'nowrap',
                        fontWeight: row.status === 'TERLAMBAT' ? 600 : 400,
                        color: row.status === 'TERLAMBAT' ? '#DC2626' : '#6B7280',
                      }}>
                        {row.estimasi}
                        {row.status === 'TERLAMBAT' && (
                          <span style={{ marginLeft: 4, fontSize: 10, color: '#DC2626' }}>● Terlambat</span>
                        )}
                      </td>
                      <td style={{ padding: '12px 16px' }}>{statusBadge(row.status)}</td>
                      <td style={{ padding: '12px 16px', textAlign: 'center' }}>
                        <MoreVertical size={15} color="#9CA3AF" style={{ cursor: 'pointer' }} />
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>

            {/* PAGINATION */}
            <div style={{
              display: 'flex', alignItems: 'center', justifyContent: 'space-between',
              padding: '12px 20px', borderTop: '1px solid #E5E7EB',
              fontSize: 12, color: '#6B7280',
            }}>
              <span>Menampilkan 1 &mdash; 4 dari 124 data</span>
              <div style={{ display: 'flex', alignItems: 'center', gap: 4 }}>
                <button style={{
                  padding: '6px 10px', border: '1px solid #E5E7EB', borderRadius: 6,
                  background: '#fff', cursor: 'pointer', display: 'flex', alignItems: 'center', justifyContent: 'center',
                }}>
                  <ChevronLeft size={14} />
                </button>
                {[1, 2, 3].map((p) => (
                  <button key={p} style={{
                    padding: '6px 12px', border: p === 1 ? '1px solid #2563EB' : '1px solid #E5E7EB',
                    borderRadius: 6, background: p === 1 ? '#2563EB' : '#fff',
                    color: p === 1 ? '#fff' : '#374151', fontWeight: 600, fontSize: 12,
                    cursor: 'pointer', fontFamily: 'inherit',
                  }}>
                    {p}
                  </button>
                ))}
                <button style={{
                  padding: '6px 10px', border: '1px solid #E5E7EB', borderRadius: 6,
                  background: '#fff', cursor: 'pointer', display: 'flex', alignItems: 'center', justifyContent: 'center',
                }}>
                  <ChevronRight size={14} />
                </button>
              </div>
            </div>
          </div>
        </main>
      </div>
    </div>
  );
}
