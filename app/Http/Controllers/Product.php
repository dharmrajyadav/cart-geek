<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;

ini_set('memory_limit', -1);
class Product extends Controller
{
    public function createmedia(Request $request)
    {
      
        $productData['product_name'] = $request->productname;
        $productData['product_price'] = $request->productprice;
        $productData['product_description'] = $request->productdescri;
        //$id = DB::table('product')->insert($productData);
        $lastId = DB::table('product')->insertGetId($productData);
        

        if($request->TotalImages > 0)
         {
             
        //Loop for getting files with index like image0, image1
            for ($x = 0; $x < $request->TotalImages; $x++) {
       
            if ($request->hasFile('images'.$x)) {
             
            $file      = $request->file('images'.$x);
            $filename  = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $picture   = date('His').'-'.$filename;
            //$images_name =$images_name.",".$name;
      
      //Save files in below folder path, that will make in public folder
            $file->move(public_path('pages/'), $picture);
      $postArray = [
          'image' => $picture,
          'product_id' => $lastId,
        ];
            DB::table('images')->insert($postArray);
             }
         }
                            
    }
  else
  {
    return response()->json(["message" => "Media missing."]);
  }
        
    }


        public function getProduct()
        {
            $sql = "SELECT a.id,a.product_name,a.product_price,a.product_description ,GROUP_CONCAT(b.image) as image FROM product a join images b on b.product_id = a.id GROUP BY a.id ,a.product_name,a.product_price,a.product_description";
            $images = DB::select(DB::raw($sql));
            return response()->json([ "images" => $images]);
        }

        public function getRowID(Request $request)
        {
              $row_Id = $request->userid;
              DB::table('product')->where('id', $row_Id)->delete();
              return $this->getProduct();
        }
        
        public function getRowIDupdate(Request $request)
        {
            $row_Id = $request->userid;
            $updateData['productDetails'] = DB::table('product')->where('id', $row_Id)->get();
            $updateData['imageDetails'] = DB::table('images')->where('product_id', $row_Id)->get();
            return response()->json([ "updateData" => $updateData]);
        }
        
        public function getDataToUpdate(Request $request)
        {
          $product_id = $request->product_id;
          $product_name = $request->product_name;
          $product_price = $request->product_price;
          $product_description = $request->product_description;
           $query = DB::table('product')
              ->where('id', $product_id)  
              ->limit(1)  
              ->update(array('product_name' => $product_name,'product_price' => $product_price,'product_description' => $product_description));   
           
        }
}

?>