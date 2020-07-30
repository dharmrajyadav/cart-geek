
<?php
        error_reporting(E_ALL & ~E_NOTICE);
?>
<!DOCTYPE html>
<html lang="en">

<head>
<meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Product-Home</title>
  
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  
 

   

</head>
<body>


    
<div class="container">
    <div class="row justify-content-center">

            

        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h3>Add product : </h3></div>
                <div class="alert alert-success" style="display:none">
                    <p></p>
                </div>
                <div class="card-body">
                    <form enctype="multipart/form-data" method="post">
                    <div class="form-group">
                            <input type="text" class="form-control" name="productname" id="productname"  placeholder="Name" required >
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="productprice" id="productprice"  placeholder="Price" required >
                        </div>
                        <div class="form-group">
                        <textarea rows="4" cols="50" type="text" class="form-control" name="productdescri" id="productdescri"  placeholder="Description" required></textarea>
                               </div>
                        
                        <div class="form-group">
                            <label for="image"><strong>Add Images:</strong></label>
                            <input type="file" class="form-control" id="image-upload" placeholder="Post Image" name="image_upload[]" required multiple>
                        </div>
                        <button type="button" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
        
</div>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                 <h4 class="modal-title" id="myModalLabel">Update Data</h4>
                        <table id="productUpdate" class="table">

                        </table>
            </div>
            <div class="modal-body">...</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="updateProduct">Update</button>
            </div>
        </div>
    </div>
</div>


<div class="container">
        <table class="table">
        <thead>
                <h5>Product-Details</h5>
      <tr>
        <th>Product-ID</th>
        <th>Product-Name</th>
        <th>Product-Price</th>
        <th>Product-Description</th>
        <th>Product-Image</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody id="productDetails">
      
    </tbody>
        </table>

</div>

</body><script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>


    jQuery("document").ready(function($) {


        $.ajax({
            url: "{{ url('getProduct') }}",
            success: function(result)
                {
                    
                    var length = result.images.length;
                    var appendRows ='';
                    for(var i = 0; i<length; i++ )
                    {
                        var imagegroup = result.images[i].image;
                        var imgName = imagegroup.split(',');
                                                    
                        appendRows+='<tr><td>'+result.images[i].id+'</td><td>'+result.images[i].product_name+
                        '</td><td>'+result.images[i].product_price+'</td><td>'
                        +result.images[i].product_description+'</td>'
                       
                        for(var j=0;j<imgName.length ; j++)
                        {
                             appendRows+= '<td><img src="pages/'+imgName[j]+'" style="display: block;max-width: 100px; max-height: 100px;  width: auto; height: auto;"></td>'
                        }    
                        appendRows+= '<td><button name="update" class="btn btn-success" id="update" data-toggle="modal" data-target="#myModal" value="'+result.images[i].id+'">Update</button></td><td><button name="delete" id="delete" class="btn btn-danger" value="'+result.images[i].id+'">Delete</button></td></tr>';
                     }
                    $("#productDetails").html(appendRows);
                  
                }
                
            });
            
          

        // Full Ajax request
        $(".btn").click(function(e) {
            // Stops the form from reloading
            e.preventDefault();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                }
            });
            
            var productname = $('#productname').val();
            var productprice = $('#productprice').val();
            var productdescri = $('#productdescri').val();
             let image_upload = new FormData();
            let TotalImages = $('#image-upload')[0].files.length; //Total Images
            let images = $('#image-upload')[0];
            for (let i = 0; i < TotalImages; i++) {
                image_upload.append('images' + i, images.files[i]);
            }
            image_upload.append('TotalImages', TotalImages);
            image_upload.append('productname', productname);
            image_upload.append('productprice', productprice);
            image_upload.append('productdescri', productdescri);
            
            $.ajax({
                url: "{{ url('addmedia') }}",              
                 type: 'POST',
                contentType: false,
                processData: false,
                data:image_upload,
                success: function(result) {

                    window.location = 'http://localhost:8000';
                    
                }
            });
        });
       
    });
   
    $(document).on('click', '#delete', function()
            { 
               var userid=$('#delete').val();
               $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                    });
              $.ajax({
                        type:'post',
                        url: "{{ url('getRowID') }}",
                        data:{userid:userid},
                        success:function(data)
                        {
                            window.location = 'http://localhost:8000';
                        }
              })
                });


                $(document).on('click', '#update', function()
            { 
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                    });
               var userid=$('#update').val();
               
               //$('#myModal').modal('show');
              $.ajax({

                            type:'post',
                            url: "{{ url('getRowIDupdate') }}",
                            data:{userid:userid},
                               
                        success:function(data)
                        {
                            var productLength = data.updateData.productDetails.length;
                            var imageLength = data.updateData.imageDetails.length;
                            var productUpdateAppend = '';
                            for( var i = 0;i<productLength ;i++)
                            {  
                                productUpdateAppend+='<tr><td>Product_Id</td><td><input type="text" name="product_id" id="product_id" value="'+data.updateData.productDetails[i].id+'" readonly></td></tr><tr><td>Product_Name</td><td><input type="text" name="product_name" id="product_name" value="'+data.updateData.productDetails[i].product_name+'" ></td></tr><tr><td>Product_Price</td><td><input type="text" name="product_price" id="product_price" value="'+data.updateData.productDetails[i].product_price+'" ></td></tr><tr><td>Product_Description</td><td><input type="text" name="product_description" id="product_description" value="'+data.updateData.productDetails[i].product_description+'" ></td></tr><tr>'
                                 for(var j = 0;j<imageLength ;j++)
                                 {
                                    productUpdateAppend+= '<td><img src="pages/'+data.updateData.imageDetails[j].image+'" style="display: block;max-width: 100px; max-height: 100px;  width: auto; height: auto;"></td>';
                                 }
                                    productUpdateAppend+='</tr>';
                            }
                            
                                $('#productUpdate').html(productUpdateAppend);
                        }

              });
              
                });

                
                $(document).on('click', '#updateProduct', function()
            { 
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                    });

                    
                    var product_id = $('#product_id').val();
                    var product_name = $('#product_name').val();
                    var product_price = $('#product_price').val();
                    var product_description = $('#product_description').val();
                    
                    $.ajax({
                                type:'post',
                                url: "{{ url('getDataToUpdate') }}",
                                data:{product_id:product_id,product_name:product_name,product_price:product_price,product_description:product_description},
                                success:function(data)
                                {
                                    window.location = 'http://localhost:8000';
                                }
                    });
              
                });
               

</script>


</html>
