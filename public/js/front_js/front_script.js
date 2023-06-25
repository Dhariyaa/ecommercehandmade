$(document).ready(function(){
   $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
   });

    $("#sort").on('change', function(){
        var sort = $(this).val();
        var url = $("#url").val();
        $.ajax ({
            url:url,
            method:"post",
            data:{sort:sort, url:url},
            success:function(data){
                $('.filter_products').html(data);
             }
            });
    });

     //update cart items
    $(document).on('click','.btnItemUpdate',function(){
        if($(this).hasClass('qtyMinus')){

            //if qtyMinus button is clicked by user
            var quantity = $(this).prev().val();
            if(quantity<=1){
                alert("Item quantity must be 1 or greater!");
                return false;
            }else{
                new_qty =parseInt(quantity)-1;

            }

            }

        if($(this).hasClass('qtyPlus')){
       //if qtyPlus button is clicked by user
            var quantity =$(this).prev().prev().val();
            new_qty =  parseInt(quantity)+1;
        }
        var cartid = $(this).data('cartid');
        $.ajax({
            data:{"cartid":cartid,"qty":new_qty},
            url:'/update-cart-item-qty',
            type:'post',
            success:function(resp){
                // alert(resp.status);
                if(resp.status==false){
                    alert(resp.message);
                }
                $("#AppendCartItems").html(resp.view);
            },
            error:function(){
                alert("Error");
            }
        });
    });


        //delete cart items

    $(document).on('click','.btnItemDelete',function(){
        var cartid = $(this).data('cartid');
        var result = confirm("Do you want to delete this item?");
        if(result){

            $.ajax({
                data:{"cartid":cartid},
                url:'/delete-cart-item',
                type:'post',
                success:function(resp){
                    $("#AppendCartItems").html(resp.view);
                },error:function(){
                    alert("Error");
                }
            });
        }


    });


    // // validate signup or regsiter form on keyup and submit
    $("#registerForm").validate({
        rules: {
            name: "required",
            mobile: {
                required: true,
                minlength: 11,
                maxlength:11,
                digits:true
            },
            email: {
                required: true,
                email: true,
                remote:"check-email"
            },
            password: {
                required: true,
                minlength: 6,
                maxlength:6,
                digits:true
            }
        },
        messages: {
            name: "Please enter your name",
            mobile: {
                required: "Please enter your mobile number",
                minlength: "Your mobile number must consists of 11 digits",
                maxlength: "Your mobile number must consists of 11 digits",
                digits:"Please enter a valid mobile number"
            },
            email: {
                required: "Please provide a email",
                email: "Please provide a valid email",
                remote:"The email provided already exist"

            },
            password: {
                required: "Please provide a password",
                minlength: "Your password must be at least 6 characters long",
                maxlength: "Your password must be not more than 6 characters long",
                digits:"Please enter a valid password"
            }
        }
    });


    // // validate login form on keyup and submit
    $("#loginForm").validate({
        rules: {
            email: {
                required: true,
                email: true,

            },
            password: {
                required: true,
                minlength: 6,
                maxlength:6,
                digits:true
            }
        },
        messages: {
            email: {
                required: "Please provide a email",
                email: "Please provide a valid email",


            },
            password: {
                required: "Please enter your password",
                minlength: "Your password must be at least 6 characters long",
                maxlength: "Your password must be not more than 6 characters long",
                digits:"Please enter a valid password"
            }
        }
    });

    //validate account from on keyup and submit
    $("#accountForm").validate({
        rules: {
            name: {
                required:true,
                accept:"[a-zA-Z]+"
            },
            mobile: {
                required:true,
                minlength: 11,
                maxlength:11,
                digits:true

            }
        },
        messages: {
            name: {
                required:"Please enter your Name",
                accept:"Please enter a valid Name!"
            },
            mobile: {
                minlength: "Your mobile number must consists of 11 digits",
                maxlength: "Your mobile number must consists of 11 digits",
                digits:"Please enter a valid mobile number"
            }
        }

    });


    //validate password from on keyup and submit
    $("#passwordForm").validate({
        rules: {
            current_pwd: {
                required:true,
                minlength:6,
                maxlength:20
            },
            new_pwd: {
                required:true,
                minlength:6,
                maxlength:20

            },
            confirm_pwd: {
                required:true,
                minlength:6,
                maxlength:20,
                equalTo:"#new_pwd"
            }

        }

    });

            //check current user password
            $("#current_pwd").keyup(function(){
                var current_pwd =$(this).val();
                // alert(current_pwd);
                $.ajax({
                    type:'post',
                    url:'/check-user-pwd',
                    data:{current_pwd:current_pwd},
                    success:function(resp){
                        // alert(resp);
                        if(resp=="false"){
                            $("#chkPwd").html("<font color='red'>The Current Password that you have inserted is Incorrect</font>");
                        }else if(resp=="true"){
                            $("#chkPwd").html("<font color='green'>The Current Password that you have inserted is Correct</font>");
                        }
                    },error:function(){
                        alert("Error");
                    }
                });

            });
});
