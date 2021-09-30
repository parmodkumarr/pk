


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
 
</head>
<body>
    <style>
        .btn-sm {
         padding: 0.05rem .5rem;
          }
    </style>

<div class="container">

<div class="row">
    <div class="col-md-4 offset-md-4">
        <div class="card mt-5">
            <div class="card-body">
                <h5 class="card-title"></h5>
                    <form action="" method="post" id="form">


                        <div class="form-group">
                        
                        <label for="">Enter Email</label>
                        <input type="text" name="email" class="form-control" placeholder="Enter Email Address" pattern="[a-zA-z0-9._%+-]+@[a-zA-z0-9.-]+\.[a-z]{2,4}$" required>
                        
                        </div>
                
                          
                        <div class="form-group email"></div>

                         <div class="form-group txt"></div>

                          <div class="form-group format"></div>

                    
                        <div class="form-group">
                            <button class="btn btn-info btn-block" type="submit" name="submit">Verified Mail</button>
                        </div>

          

                        

                       


                       


                    </form>
            </div>
        </div>
    </div>
</div>

</div>
<script type = "text/javascript" src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js">
</script>
<script type = "text/javascript" language = "javascript">
 $(document).ready(function() {
     $("#form").on("submit", function(event) {
        event.preventDefault();
        $('.email').html(null);
        $('.txt').html(null);
        $('.format').html(null);
           var email1= $('input[name=email]').val();

    // function validateEmail(email) {
    //   const re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    //   return re.test(email1);
    // }

    // if (validateEmail(email1)) {
    //     var formData = {
    //         'email': $('input[name=email]').val() //for get email 
    //     }
    // } else {
        
    //     $('.email').html("<label>Email</label>: <span>"+email1+"</span>");
    //     $('.txt').html("<label>Mail Status</label>: <span class='btn btn-danger btn-sm'>Not Verified</span>");
    //     $('.format').html("<label>Format Mail Status</label>: <span class='btn btn-danger btn-sm'>Not Verified</span>");
    //     return;
    // }
          // var email=email1.split('@');
          //   if(email[1]=='gmail.com'){
          //    var formData = {
          //       'email': $('input[name=email]').val() //for get email 
          //          };
          //   }else{
                   
          //       $('.email').html("<label>Email</label>: <span>"+email1+"</span>");
          //        $('.txt').html("<label>Mail Status</label>: <span class='btn btn-danger btn-sm'>Not Verified</span>");
          //        $('.format').html("<label>Format Mail Status</label>: <span class='btn btn-danger btn-sm'>Not Verified</span>");
          //     return;
          //   }
        var formData = {
            'email': $('input[name=email]').val() //for get email 
        };

            $.ajax({
                url: "ajax1.php",
                type: "post",
                data: formData,
                dataType: "json",
                success: function(d) {
                   // console.log(data);
                    var html;
                    var html1;
                    var html1;
            
                     html1="<label>Email</label>: <span>"+d.email+"</span>";
                     if(d.is_smtp_valid.text=="TRUE"){
                     html="<label>Mail Status</label>: <span class='btn btn-success btn-sm'>Verified</span>";
                            }else{
                         html="<label>Mail Status</label>: <span class='btn btn-danger btn-sm'>Not Verified</span>";
                     }

                      if(d.is_valid_format.text!="FALSE"){
                     html2="<label>Format Mail Status</label>: <span class='btn btn-success btn-sm'>Verified</span>";
                            }else{
                         html2="<label>Format Mail Status</label>: <span class='btn btn-danger btn-sm'>Not Verified</span>";
                     }


                     $('.email').html(html1);

                     $('.txt').html(html);
                        $('.format').html(html2);
                }
            });
        });
         });
      </script>


    
</body>
</html>