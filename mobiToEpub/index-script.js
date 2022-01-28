var fileTypes = ['mobi'];  // acceptable file types

function readURL(input)
{
  if(input.files && input.files[0])
  {
    var extension = input.files[0].name.split('.').pop().toLowerCase();  // file extension from input file
    var isSuccess = fileTypes.indexOf(extension) > -1;  // is extension in acceptable types

    if(!isSuccess)
    {
      $(input).closest('.uploadDoc').find(".docErr").fadeIn();
      setTimeout(function() {
  	   	$('.docErr').fadeOut('slow');
  		}, 9000);
    }

    return isSuccess;
  }
  return false;
}

function convertFile()
{
  var input = $('#file').get(0);
  var isSuccess = readURL(input);

  if(!isSuccess)
  {
    $(input).closest('.uploadDoc').find(".docErr").fadeIn();
    setTimeout(function() {
      $('.docErr').fadeOut('slow');
    }, 9000);
    return;
  }

  if(window.FormData == undefined)
  {
    alert("Browser does not support the operation");
    return;
  }

  var formData = new FormData($("#form-upload")[0]);
  //formData.append("value", value);

  $(".btn-next").off('click');
  $(".btn-next").addClass('btn-unbind');

  $.ajax({
      type: 'POST',
      url: 'mobiToEpub.php',
      data: formData,
      dataType: 'json',
      processData: false,
      contentType: false,
      cache: false,
      success: function(result)
      {
        //console.log(JSON.stringify(result));
        if(result.error === true)
        {
          $(".btn-next").on('click',function()
          {
            convertFile();
          });
          $(".btn-next").removeClass('btn-unbind');
          alert(result.msg);
          return;
        }

        $(".btn-new").show();
        $(".btn-new").attr("href", result.url);
      },
      error: function(XMLHttpRequest, textStatus, errorThrow)
      {
        $(".btn-next").on('click',function()
        {
          convertFile();
        });
        $(".btn-next").removeClass('btn-unbind');
        alert("An error occurred.");
      }
  });
}

$(document).ready(function()
{
  $(".btn-new").hide();

  $(document).on('change','.up', function()
  {
    var id = $(this).attr('id'); // gets the filepath and filename from the input
    var profilePicValue = $(this).val();
    var fileNameStart = profilePicValue.lastIndexOf('\\'); // finds the end of the filepath
    profilePicValue = profilePicValue.substr(fileNameStart + 1).substring(0,20); // isolates the filename
    //var profilePicLabelText = $(".upl"); // finds the label text
    if(profilePicValue != '')
    {
      //console.log($(this).closest('.fileUpload').find('.upl').length);
      $(this).closest('.fileUpload').find('.upl').html(profilePicValue); // changes the label text
    }
  });

  $(".btn-next").on('click',function()
  {
    convertFile();
  });
});

$( document ).ajaxStart(function()
{
    $("#spinner").show();
});

$( document ).ajaxStop(function()
{
    $("#spinner").hide();
});
