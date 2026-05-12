<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['name'=>'Dimsum Kukus','category'=>'dimsum','description'=>'Dimsum kukus isian udang & ayam pilihan, lembut dan gurih.','price'=>25000,'stock'=>50,'status'=>'ready','image'=>'https://images.unsplash.com/photo-1563245372-f21724e3856d?w=400'],
            ['name'=>'Dimsum Goreng','category'=>'dimsum','description'=>'Dimsum goreng renyah dengan isian daging ayam pilihan.','price'=>28000,'stock'=>40,'status'=>'ready','image'=>'https://images.unsplash.com/photo-1574484284002-952d92456975?w=400'],
            ['name'=>'Siomay Spesial','category'=>'dimsum','description'=>'Siomay lembut bumbu kacang spesial khas Ummilaa Kitchen.','price'=>22000,'stock'=>35,'status'=>'ready','image'=>'https://images.unsplash.com/photo-1617196034183-421b4040ed20?w=400'],
            ['name'=>'Frozen Dimsum','category'=>'dimsum','description'=>'Dimsum beku siap masak, praktis untuk stok di rumah.','price'=>45000,'stock'=>20,'status'=>'preorder','image'=>'https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?w=400'],
            ['name'=>'Risol Mayo','category'=>'risol','description'=>'Risol isian mayo, wortel & telur rebus yang creamy.','price'=>20000,'stock'=>60,'status'=>'ready','image'=>'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=400'],
            ['name'=>'Risol Keju','category'=>'risol','description'=>'Risol dengan isian keju mozzarella meleleh yang nikmat.','price'=>22000,'stock'=>45,'status'=>'ready','image'=>'https://images.unsplash.com/photo-1606755962773-d324e0a13086?w=400'],
            ['name'=>'Risol Ragout','category'=>'risol','description'=>'Risol dengan isian ragout ayam spesial yang kaya rasa.','price'=>23000,'stock'=>0,'status'=>'preorder','image'=>'https://images.unsplash.com/photo-1559847844-5315695dadae?w=400'],
            ['name'=>'Cilok Bumbu Kacang','category'=>'perpentolan','description'=>'Cilok kenyal dengan bumbu kacang spesial khas Ummilaa.','price'=>15000,'stock'=>80,'status'=>'ready','image'=>'https://images.unsplash.com/photo-1559847844-5315695dadae?w=400'],
            ['name'=>'Bakso Goreng','category'=>'perpentolan','description'=>'Bakso goreng renyah di luar, kenyal di dalam.','price'=>20000,'stock'=>55,'status'=>'ready','image'=>'https://images.unsplash.com/photo-1574484284002-952d92456975?w=400'],
            ['name'=>'Pentol Crispy','category'=>'perpentolan','description'=>'Pentol crispy dengan berbagai pilihan saus pedas manis.','price'=>18000,'stock'=>70,'status'=>'ready','image'=>'https://images.unsplash.com/photo-1563245372-f21724e3856d?w=400'],
            ['name'=>'Banana Crunchy','category'=>'crunchy','description'=>'Pisang crispy berlapis coklat, renyah di luar lembut di dalam.','price'=>18000,'stock'=>40,'status'=>'ready','image'=>'https://images.unsplash.com/photo-1606755962773-d324e0a13086?w=400'],
            ['name'=>'Jamur Crispy','category'=>'crunchy','description'=>'Jamur tiram crispy gurih dengan berbagai varian bumbu.','price'=>20000,'stock'=>35,'status'=>'ready','image'=>'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=400'],
            ['name'=>'Tahu Crispy','category'=>'crunchy','description'=>'Tahu crispy renyah dengan bumbu balado atau original.','price'=>15000,'stock'=>50,'status'=>'ready','image'=>'https://images.unsplash.com/photo-1559847844-5315695dadae?w=400'],
            ['name'=>'Ayam Crispy','category'=>'crunchy','description'=>'Ayam crispy juicy di dalam, super renyah di luar.','price'=>30000,'stock'=>25,'status'=>'preorder','image'=>'https://images.unsplash.com/photo-1617196034183-421b4040ed20?w=400'],
            ['name'=>'Es Teh Manis','category'=>'minuman','description'=>'Es teh manis segar, cocok menemani camilan favorit Anda.','price'=>8000,'stock'=>100,'status'=>'ready','image'=>'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=400'],
            ['name'=>'Es Jeruk Peras','category'=>'minuman','description'=>'Es jeruk peras segar dari buah jeruk pilihan yang manis.','price'=>10000,'stock'=>80,'status'=>'ready','image'=>'https://images.unsplash.com/photo-1621506289937-a8e4df240d0b?w=400'],
            ['name'=>'Es Cincau Hijau','category'=>'minuman','description'=>'Es cincau hijau segar dengan santan dan gula aren khas.','price'=>12000,'stock'=>60,'status'=>'ready','image'=>'https://images.unsplash.com/photo-1563805042-7684c019e1cb?w=400'],
        ];

        foreach ($products as $p) {
            Product::create($p);
        }
    }
}