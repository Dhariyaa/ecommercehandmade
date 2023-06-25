jQuery(document).ready(function(){
    //check if admin password is correct or incorrect
    //changed all $ to jQuery because was not working

    jQuery("#present_password").keyup(function(){
       var present_password = jQuery ("#present_password").val();
       //alert(present_password);
       jQuery.ajax({
            type:'post',
            url:'/admin/check-present-password',
            data:{present_password:present_password},
            success:function(resp){
                //alert(resp);
                if(resp=="false"){
                    jQuery("#checkPresentPassword").html("<font color=burgandy>The Present Password is incorrect!</font>");
                }else if(resp=="true"){
                    jQuery("#checkPresentPassword").html("<font color=blue>The Present Password is correct!</font>");
                }
            },error:function(){
                alert("Error");
            }

        });
    });
            //update section (main category) status
            $(".updateSectionStatus").click(function(){
                var status = $(this).text();
                var section_id = $(this).attr("section_id");
                //alert(status);
                //alert(section_id);
                jQuery.ajax({
                    type:'post',
                    url:'/admin/update-section-status',
                    data:{status:status,section_id:section_id},
                    success:function(resp){
                        //alert(resp['status']);
                        //alert(resp['section_id']);
                        if (resp['status']==0){
                            $("#section-"+section_id).html("<a class='updateSectionStatus' href='javascript:void(0)'>Inactive</a>");
                        }else if(resp['status']==1){
                            $("#section-"+section_id).html("<a class='updateSectionStatus' href='javascript:void(0)'>Active</a>");
                        }

                    },error:function(){
                        alert("Error");
                    }
                });
            });

            //update categories status
            // $(".updateCategoryStatus").click(function(){
               $(document).on("click",".updateCategoryStatus",function(){
                var status = $(this).text();
                var category_id = $(this).attr("category_id");
                //alert(status);
                //alert(section_id);
                jQuery.ajax({
                    type:'post',
                    url:'/admin/update-category-status',
                    data:{status:status,category_id:category_id},
                    success:function(resp){
                        //alert(resp['status']);
                        //alert(resp['section_id']);
                        if (resp['status']==0){
                            $("#category-"+category_id).html("<a class='updateCategoryStatus' href='javascript:void(0)'>Inactive</a>");
                        }else if(resp['status']==1){
                            $("#category-"+category_id).html("<a class='updateCategoryStatus' href='javascript:void(0)'>Active</a>");
                        }
                    },
                    error:function(){
                        alert("Error");
                    }
                });
            });


            //include category level
            $('#section_id').change(function(){
                var section_id =$(this).val();
                //alert(section_id);
               $.ajax({
                    type:'post',
                    url:'/admin/include-categories-level',
                    data:{section_id:section_id},
                    success:function(resp){
                        $("#includeCategoriesLevel").html(resp);

                    },error:function(){
                        alert("Error");
                    }

                });
            });

            //confirmation of delete of records of project
           /* $(".ConfirmDelete").click(function(){
                var name = $(this).attr("name");
                if(confirm("Confirm deleting this? "+name+"?")){
                    return true;
                }
                return false;

            });*/

             //update product status
             $(".updateProductStatus").click(function(){
                var status = $(this).text();
                var product_id = $(this).attr("product_id");
                console.log(product_id, status);
                //alert(status);
                //alert(section_id);
                jQuery.ajax({
                    type:'post',
                    url:'/admin/update-product-status',
                    data:{status:status,product_id:product_id},
                    success:function(resp){
                        //alert(resp['status']);
                        //alert(resp['section_id']);
                        if (resp['status']==0){
                            $("#product-"+product_id).html("<a class='updateProductStatus' href='javascript:void(0)'>Inactive</a>");
                        }else if(resp['status']==1){
                            $("#product-"+product_id).html("<a class='updateProductStatus' href='javascript:void(0)'>Active</a>");
                        }

                    },error:function(){
                        alert("Error");
                    }
                });
            });

            //update attribute status
            $(".updateAttributeStatus").click(function(){
                var status = $(this).text();
                var attribute_id = $(this).attr("attribute_id");
                //alert(status);
                //alert(section_id);
                jQuery.ajax({
                    type:'post',
                    url:'/admin/update-attribute-status',
                    data:{status:status,attribute_id:attribute_id},
                    success:function(resp){
                        //alert(resp['status']);
                        //alert(resp['section_id']);
                        if (resp['status']==0){
                            $("#attribute-"+attribute_id).html("Inactive");
                        }else if(resp['status']==1){
                            $("#attribute-"+attribute_id).html("Active");
                        }

                    },error:function(){
                        alert("Error");
                    }
                });
            });

             //update banner status
             $(".updateBannerStatus").click(function(){
                var status = $(this).text();
                var banner_id = $(this).attr("banner_id");
                console.log(banner_id, status);
                //alert(status);
                //alert(section_id);
                jQuery.ajax({
                    type:'post',
                    url:'/admin/update-banner-status',
                    data:{status:status,banner_id:banner_id},
                    success:function(resp){
                        //alert(resp['status']);
                        //alert(resp['section_id']);
                        if (resp['status']==0){
                            $("#banner-"+banner).html("<a class='updateBannerStatus' href='javascript:void(0)'>Inactive</a>");
                        }else if(resp['status']==1){
                            $("#banner-"+banner).html("<a class='updateBannerStatus' href='javascript:void(0)'>Active</a>");
                        }

                    },error:function(){
                        alert("Error");
                    }
                });
            });




            //confirm to delete using sweetalert2

            // $(".ConfirmDelete").click(function(){
                $(document).on("click", ".ConfirmDelete", function(){
                var record = $(this).attr("record");
                var recordId = $(this).attr("recordId");
                Swal.fire({
                    title: 'Sure you want to delete this?!',
                    text: "No changes after taking this decision!",
                    icon: 'warning',
                    background: 'orange',
                    showCancelButton: true,
                    confirmButtonColor: '#1f780a',
                    cancelButtonColor: '#f58cd4',
                    confirmButtonText: 'Yes, I want to delete it!',
                    cancelButtonText: "No, cancel please!"
                  }).then((result) => {
                    if (result.value) {
                      window.location.href="/admin/delete-"+record+"/"+recordId;
                    }
                  });

            });

            //product attribute for the add and remove script
            var maxField = 10; //Input fields increment limitation
            var addButton = $('.add_button'); //Add button selector
            var wrapper = $('.field_wrapper'); //Input field wrapper
            var fieldHTML = '<div><div style="height:10px;"></div><input type="text" name="sku[]" style="width: 120px" placeholder="SKU"/>&nbsp;<input type="text" name="price[]" style="width: 120px" placeholder="Price"/>&nbsp;<input type="text" name="stock[]" style="width: 120px" placeholder="Stock"/>&nbsp;<a href="javascript:void(0);" class="remove_button">Delete</a></div>'; //New input field html
            var x = 1; //Initial field counter is 1

            //Once add button is clicked
            $(addButton).click(function(){
                //Check maximum number of input fields
                if(x < maxField){
                    x++; //Increment field counter
                    $(wrapper).append(fieldHTML); //Add field html
                }
            });

            //Once remove button is clicked
            $(wrapper).on('click', '.remove_button', function(e){
                e.preventDefault();
                $(this).parent('div').remove(); //Remove field html
                x--; //Decrement field counter
            });

});


