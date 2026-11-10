(function ($, settings) {
  'use strict';

  function bindCollection(listSelector, templateSelector, addSelector, rowSelector, removeSelector) {
    var list = $(listSelector);
    var template = $(templateSelector).html();

    if (!list.length || !template) {
      return;
    }

    $(addSelector).on('click', function () {
      list.append(template.replace(/__INDEX__/g, 'new' + Date.now()));
    });

    list.on('click', removeSelector, function () {
      $(this).closest(rowSelector).remove();
    });
  }

  function bindImagePicker(selectSelector, removeSelector, containerSelector, urlSelector, previewSelector, title) {
    $(document).on('click', selectSelector, function (event) {
      event.preventDefault();

      var container = $(this).closest(containerSelector);
      var frame = wp.media({
        title: title,
        button: { text: settings.useImage },
        multiple: false,
        library: { type: 'image' }
      });

      frame.on('select', function () {
        var image = frame.state().get('selection').first().toJSON();
        var preview = $('<img>', { src: image.url, alt: '' }).css({
          display: 'block',
          width: '150px',
          height: '110px',
          objectFit: 'cover'
        });

        container.find(urlSelector).val(image.url);
        container.find(previewSelector).empty().append(preview);
      });

      frame.open();
    });

    $(document).on('click', removeSelector, function (event) {
      event.preventDefault();

      var container = $(this).closest(containerSelector);
      var placeholder = $('<div>').text(settings.noImage).css({
        width: '150px',
        height: '110px',
        display: 'grid',
        placeItems: 'center',
        background: '#f0f0f1'
      });

      container.find(urlSelector).val('');
      container.find(previewSelector).empty().append(placeholder);
    });
  }

  bindCollection(
    '#maa-industry-support-list',
    '#tmpl-maa-industry-support-row',
    '#maa-add-industry-support',
    '.maa-industry-support-row',
    '.maa-remove-industry-support'
  );
  bindCollection(
    '#maa-material-supply-list',
    '#tmpl-maa-material-supply-row',
    '#maa-add-material-supply',
    '.maa-material-supply-row',
    '.maa-remove-material-supply'
  );
  bindCollection(
    '#maa-infrastructure-item-list',
    '#tmpl-maa-infrastructure-item',
    '#maa-add-infrastructure-item',
    '.maa-infrastructure-item-row',
    '.maa-remove-infrastructure-item'
  );

  bindImagePicker(
    '.maa-select-industry-image',
    '.maa-remove-industry-image',
    '.maa-industry-support-row',
    '.maa-industry-image-url',
    '.maa-industry-image-preview',
    settings.chooseIndustryImage
  );
  bindImagePicker(
    '.maa-select-material-intro-image',
    '.maa-remove-material-intro-image',
    '.maa-material-intro-image',
    '.maa-material-intro-image-url',
    '.maa-material-intro-image-preview',
    settings.chooseMaterialIntroImage
  );
  bindImagePicker(
    '.maa-select-infrastructure-image',
    '.maa-remove-infrastructure-image',
    '.maa-infrastructure-image-row',
    '.maa-infrastructure-image-url',
    '.maa-infrastructure-image-preview',
    settings.chooseInfrastructureImage
  );
})(jQuery, window.maaBaulaSectionFields || {});
