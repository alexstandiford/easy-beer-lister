jQuery(document).ready(function($){
  // Uploading files
  var fileFrame;
  var wpMediaPostID = wp.media.model.settings.post.id; // Store the old id
  var fieldID;
  var glassLayout = [$('.ebl-glass-shape-wrapper .glass-shape.mod--selected').data('glass-shape'),$('.ebl-glass-layout-wrapper .glass-shape.mod--selected').data('glass-layout')];
  console.log(glassLayout);
  var currentGlassShape;

  /**
   * gallery handler
   */
  $('#upload_image_button').on('click',function(event){
    event.preventDefault();
    var fieldID = $(this).parent().data('field-id');
    // If the media frame already exists, reopen it.
    if(fileFrame){
      // Set the post ID to what we want
      fileFrame.uploader.uploader.param('post_id',eblAdmin[fieldID].setToPostID);
      // Open frame
      fileFrame.open();
      return;
    }else{
      // Set the wp.media post id so the uploader grabs the ID we want when initialised
      wp.media.model.settings.post.id = eblAdmin[fieldID].setToPostID;
    }
    // Create the media frame.
    fileFrame = wp.media.frames.file_frame = wp.media({
      title:    'Select a image to upload',
      button:   {
        text: 'Use selected images'
      },
      multiple: true	// Set to true to allow multiple files to be selected
    });
    // When an image is selected, run a callback.
    fileFrame.on('select',function(){

      var attachments = [];
      var item;
      var selection = fileFrame.state().get('selection');
      selection.map(function(attachment){
        jQuery('#' + eblAdmin[fieldID].previewTarget).html('');
        attachment = attachment.toJSON();
        attachments.push(attachment);

        for(i = 0; i < attachments.length; i++){
          if(i === 0){
            item = attachments[i].id;
          }
          else{
            item += ',' + attachments[i].id;
          }
          jQuery('<img height="100" src="' + attachments[i].url + '">').appendTo('#' + eblAdmin[fieldID].previewTarget);
        }

        jQuery('#' + eblAdmin[fieldID].inputTarget).attr('value',item);
      });
      attachments = [];
      // Restore the main post ID
      wp.media.model.settings.post.id = wpMediaPostID;
    });
    // Finally, open the modal
    fileFrame.open();
  });

  $('.upload_single_image_button').on('click',function(event){
    event.preventDefault();
    fieldID = $(this).parent().data('field-id');

    // If the media frame already exists, reopen it.
    if(fileFrame){
      // Set the post ID to what we want
      fileFrame.uploader.uploader.param('post_id',eblAdmin[fieldID].setToPostID);
      // Open frame
      fileFrame.open();
      return;
    }else{
      // Set the wp.media post id so the uploader grabs the ID we want when initialised
      wp.media.model.settings.post.id = eblAdmin[fieldID].setToPostID;
    }
    // Create the media frame.
    fileFrame = wp.media.frames.file_frame = wp.media({
      title:    'Select a image to upload',
      button:   {
        text: 'Use this image'
      },
      multiple: false	// Set to true to allow multiple files to be selected
    });
    // When an image is selected, run a callback.
    fileFrame.on('select',function(){
      var attachments = [];
      var item;
      var selection = fileFrame.state().get('selection');
      selection.map(function(attachment){
        jQuery('.' + eblAdmin[fieldID].previewTarget).html('');
        attachment = attachment.toJSON();
        attachments.push(attachment);

        for(i = 0; i < attachments.length; i++){
          if(i === 0){
            item = attachments[i].id;
          }
          else{
            item += ',' + attachments[i].id;
          }
          jQuery('<img height="100" src="' + attachments[i].url + '">').appendTo('.' + eblAdmin[fieldID].previewTarget);
          if(eblAdmin[fieldID].fieldType === 'ebl-label'){
            jQuery('.ebl-bottom-label').attr('xlink:href',attachments[i].sizes.ebl_bottom_label.url);
          }
          if(eblAdmin[fieldID].fieldType === 'ebl-top-label'){
            jQuery('.ebl-top-label').attr('xlink:href',attachments[i].sizes.ebl_top_label.url);
          }

        }

        jQuery('.' + eblAdmin[fieldID].inputTarget).attr('value',item);
      });
      attachments = [];
      // Restore the main post ID
      wp.media.model.settings.post.id = wpMediaPostID;
    });
    // Finally, open the modal
    fileFrame.open();
  });

  // Restore the main ID when the add media button is pressed
  $('a.add_media').on('click',function(){
    wp.media.model.settings.post.id = wpMediaPostID;
  });

  /**
   * Handles the SRM value interface
   */
  $('.ebl-srm-value').on('click',function(){
    var fieldID = $(this).parent().data('wrapper-id');
    $('.ebl-srm-value.mod--selected').removeClass('mod--selected');
    $('#' + eblAdmin[fieldID].inputTarget).attr('value',$(this).data('srm-value'));
    $(this).addClass('mod--selected');
    var srmHex = $(this).data('srm-hex');
    $('.ebl-glass').each(function(){
      $(this).css('color',srmHex);
    });
  });

  /**
   * Handles the Glass shape and layout interface
   */
  $('.ebl-glass-shape-wrapper .glass-shape').on('click',function(){
    var fieldID = $(this).parent().parent().data('wrapper-id');
    $('.ebl-glass-shape-wrapper .glass-shape.mod--selected').removeClass('mod--selected');
    glassLayout[0] = $(this).data('glass-shape');
    $('#' + eblAdmin[fieldID].inputTarget).attr('value',glassLayout);
    $(this).addClass('mod--selected');
    currentGlassShape = $(this).html();
    $('.ebl-glass-layout-wrapper .glass-shape svg').each(function(){
      if(!$(this).hasClass('ebl-glass-bottle')) $(this).html(currentGlassShape);
    });
  });

  $('.ebl-glass-layout-wrapper .glass-shape').on('click',function(){
    var fieldID = $(this).parent().parent().data('wrapper-id');
    $('.ebl-glass-layout-wrapper .glass-shape.mod--selected').removeClass('mod--selected');
    glassLayout[1] = $(this).data('glass-layout');
    $('#' + eblAdmin[fieldID].inputTarget).attr('value',glassLayout);
    $(this).addClass('mod--selected');
  });

  /**
   * Handles the availability start/end date
   */
  var availabilityStartDate = $('#ebl-availability-start-date');
  availabilityStartDate.on('change',function(){
    if($('option:selected',this).val() === "0"){
      $('#ebl-availability-end-date-wrapper').addClass('mod--hidden');
    }
    else{
      $('#ebl-availability-end-date-wrapper').removeClass('mod--hidden');
    }
  });

  if($('option:selected',availabilityStartDate).val() !== 'year-round') $('#ebl-availability-end-date-wrapper').removeClass('mod--hidden');
});