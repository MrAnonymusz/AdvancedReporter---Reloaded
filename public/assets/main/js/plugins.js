(function($) {
  // Random String Generator
  function randomStringGenerate(length) {
    var result = '';
    var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var charactersLength = characters.length;
    
    for ( var i = 0; i < length; i++ ) {
      result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    
    return result;
  }

  // Generates a custom alert
  $.fn.generateAlert = function(options) {
    var container = this;

    var options = options;

    var defaults = {
      "icon": "fas fa-success-cirlce",
      "css_class": "custom-alert-primary"
    };

    if(typeof options.icon == "undefined")
    {
      var icon = defaults.icon;
    }
    else
    {
      var icon = options.icon;
    }

    if(typeof options.css_class == "undefined") {
      var css_class = defaults.css_class;
    }
    else
    {
      var css_class = options.css_class;
    }

    var template = "";

    var text = options.text;

    template += '<div class="custom-alert ' + css_class +'">';

    // Left Part
    template += '<div class="alert-box-left">'

    template += '<i class="' + icon + ' alert-icon"></i>';

    template += '</div>';
    // Left Part

    // Right Part
    template += '<div class="alert-box-right">'

    template += text;

    template += '</div>';
    // Right Part

    template += '</div>';

    container.html(template);
  }

  // Word Counter
  $.fn.wordCounter = function(options) {
    var selector = this;

    var id = selector.attr('id');

    var textarea = $(options.textarea);

    var max_length = $('#' + id + ' > #w-total');
    var current_length = $('#' + id + ' > #w-current');

    textarea.attr('maxlength', max_length.text());

    var percentage = Math.floor((current_length.text() / max_length.text()) * 100);

    if(percentage < 60)
    {
      if(selector.hasClass('text-warning'))
      {
        selector.removeClass('text-warning');
      }

      if(selector.hasClass('text-danger'))
      {
        selector.removeClass('text-danger');
      }
    }
      
    if(percentage > 60)
    {
      selector.addClass('text-warning');
    }
      
    if(percentage > 80)
    {
      if(selector.hasClass('text-warning'))
      {
        selector.removeClass('text-warning');
      }

      selector.addClass('text-danger');
    }

    textarea.keyup(function() {
      percentage = Math.floor((current_length.text() / max_length.text()) * 100);

      if(percentage < 60)
      {
        if(selector.hasClass('text-warning'))
        {
          selector.removeClass('text-warning');
        }

        if(selector.hasClass('text-danger'))
        {
          selector.removeClass('text-danger');
        }
      }
      
      if(percentage > 60)
      {
        selector.addClass('text-warning');
      }
      
      if(percentage > 80)
      {
        if(selector.hasClass('text-warning'))
        {
          selector.removeClass('text-warning');
        }

        selector.addClass('text-danger');
      }

      current_length.text($(this).val().length);
    });
  };

  // Bootstrap File Input Change Text
  $.fn.bootstrapFileInput = function() {
    $.each(this, function() {
      $(this).on('change',function(){
        var raw_file_name = $(this).val().split('\\');
        var arr_length = raw_file_name.length - 1;
        var file_name = raw_file_name[arr_length];
        
        $(this).next('.custom-file-label').html(file_name);
      });
    });
  };
}(jQuery));