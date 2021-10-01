var pageContainer = $('#pagination');
var url = pageContainer.data('url');
var sortEntity = '';
var sortOrder = '';
var perPage = $('#perPage').val();
var keyword = '';
var ajaxReq = null;
var formData = '';
var container_type = '';
var token = $('meta[name="_token"]').attr('content');

var options = {
    autoClose: true,
};


$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$('body').on('submit', '.ajax-submit', function(e) {
        e.preventDefault();
        var that = $(this);
        var formId = $(this).attr('id');
        var formdataId = $(this).data("id");

        $('#'+formId).find(".error").remove();
        $('#'+formId).find(".border-red").removeClass('border-red');

        var form = $('#'+formId);
        $.ajax({
            type: $(this).attr('method'),
            url: $(this).attr('action'),
            data: new FormData(this),
            processData: false,
            contentType: false,
            beforeSend: function() {
                showLoader();
            },
            success: function(data) {
                if (data.success)
                {
                    $(this).find("button[type='submit']").prop('disabled', true);
                    hideLoader();
                    $('.modal').modal('hide');
                    if (data.message != '') {
                        $('.__modal_message').html(data.message);
                        $('.__modal').modal('show');
                    }

                    if (data.extra.reload) {
                        $('.__modal').on('hidden.bs.modal', function(e) {
                            window.location.reload();
                        });
                    }
                    if (data.extra.message && data.extra.message != '') {
                        refreshDiv();
                        setTimeout(function(){
                            $('#success_message').html(data.extra.message);
                            that[0].reset();
                        }, 1000);
                    }
                    if (data.extra.redirect)
                    {
                        if (data.message != '') {
                            $('.__modal').on('hidden.bs.modal', function(e) {
                                window.location.href = data.extra.redirect;
                            });
                        } else {
                            window.location.href = data.extra.redirect;
                        }
                    }
                } else {
                    hideLoader();
                    console.log(data.errors);
                    $.each(data.errors, function(i, v) {
                        var error = '<small class="form-text text-muted">'+ v +'</small>';
                        var split = i.split('.');
                        if (split[2]) {
                            var ind = split[0] + '[' + split[1] + ']' + '[' + split[2] + ']';
                            form.find("[name='" + ind + "']").addClass('border-red');
                            form.find("[name='" + ind + "']").parent().append(error);
                        } else if (split[1]) {
                            var ind = split[0] + '[' + split[1] + ']';
                            form.find("[name='" + ind + "']").addClass('border-red');
                            form.find("[name='" + ind + "']").parent().append(error);
                        } else {
                            form.find("[name='" + i + "']").addClass('border-red');
                            form.find("[name='" + i + "']").parent().append(error);
                        }
                    });
                }
            },
            error: function(data) {
                console.log('An error occurred.');
            }
        });
    });

$( "input" ).keyup(function() {
    var value = $(this).val();
    var form = $(this).closest('form')[0];
    if(value != null && value != ''){
        $(form).find("button[type='submit']").prop('disabled', false);
    }
});

function showLoader() {
    $('.loading').show();
}

function hideLoader() {
    $('.loading').hide();
}

$('body').on('click', '.__toggle', function(e) {
    var id = $(this).attr('data-id');
    e.preventDefault();
    var option = { _token: token, _method: 'post', id:id };
    var route = $(this).attr('data-route');
    $.ajax({
        type: 'post',
        url: route,
        data: option,
        success: function(data) {
            refreshDiv();
        },
        error: function(data) {
            alert('An error occurred.');
        }
    });
});

$('body').on('click', '.__delete', function(e) {
     var data_heading = $(this).attr('data-heading');
    var data_message = $(this).attr('data-message');
    $('#delete_heading').html(data_heading);
    $('#delete_message').html(data_message);
    var id = $(this).attr('data-id');
    e.preventDefault();
    var option = { _token: token, _method: 'DELETE', id:id };
    var route = $(this).attr('data-route');
    $('#confirm').modal({ backdrop: 'static', keyboard: false }).one('click', '#delete_record', function (e) {
        showLoader();
        $.ajax({
            type: 'post',
            url: route,
            data: option,
            success: function(data) {
                hideLoader();
                $('#confirm').modal('toggle');
                refreshDiv();
            },
            error: function(data) {
                hideLoader();
                alert('An error occurred.');
            }
        });
    });
});

$('body').on('click', '.__drop', function(e) {
    var data_heading = $(this).attr('data-heading');
    var data_message = $(this).attr('data-message');
    $('#delete_heading').html(data_heading);
    $('#delete_message').html(data_message);
    var route = $(this).attr('data-url');
    $('#confirm').modal({ backdrop: 'static', keyboard: false }).one('click', '#delete_record', function (e) {
            var option = { _token: token };
            showLoader();
            $.ajax({
                type: 'post',
                url: route,
                data: option,
                success: function(data) {
                    if (data.success && data.status == 200) {
                        showLoader();
                        if (data.message != '')
                        {
                            hideLoader();
                            $('#confirm').modal('toggle');
                            refreshDiv();
                        }
                    }
                },
                error: function(data) {
                    console.log('An error occurred.');
                }
            });
        });
    e.preventDefault();
}).on('click', '._back', function(event) {
    history.back(1);
});

$('body').on('click', '.__statusUpdate', function(e) { console.log( $(this));
    var data_heading = $(this).attr('data-heading');
    var project_id = $(this).attr('data-project-id');
    var data_message = $(this).attr('data-message');//alert(data_heading);
    $('#status_heading').html(data_heading);
    $('#status_message').html(data_message);
    $('#project_id').val(project_id);
});

function refreshDiv(){
    $("#data_table").load(location.href+" #data_table>*","");
}

function showForm(formId){
    $('#form_'+formId).show();
    $('#row_'+formId).hide();
    $('#button_'+formId).removeClass('btn-disabled');
}

function hideForm(formId){
    $('#form_'+formId).hide();
    $('#row_'+formId).show();
    $('#button_'+formId).addClass('btn-disabled');
}


$(document).ready(function(){
	$(".toggle-main").click(function(){
		$("body").addClass("close-toggle");
	});
    $(".toggle-close").click(function(){
	  $("body").removeClass("close-toggle");
    });
});


$(".calculateAmount").on("change", function(){
   calculateDiscount();
});

$(".calculateAmount").on("keyup", function(){
    calculateDiscount();
});


function calculateDiscount() {
    var actual_price = $('#actual_price').val();
    var discount_type = $('#discount_type').val();
    var discount = $('#discount').val();
    //  var final_price = $('#final_price').val();

    if(discount_type == 1) {
        var finalPrice = actual_price - discount;
    } else {
        var discountedAmount = (actual_price*discount)/100;
        var finalPrice = actual_price - discountedAmount;
    }
    $('#final_price').val(finalPrice);
    $('#final_price_hidden').val(finalPrice);
}

$('.reloadPage').click(function () {
    location.reload();
});

function readImgURL(input, imgId) {
    if (input.files && input.files[0]) {
        reader = new FileReader();
        reader.onload = function (e) {
            $('#'+imgId).attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

