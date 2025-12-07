/*jslint browser: true*/
/*global $, jQuery, alert*/

$(document).ready(function () {
  'use strict';
  $("#sidetoggler").on("click", function () {
    $(this).toggleClass('collapsed')
    $('#sidebar').toggleClass('active');
    $('#page-content').toggleClass('padding');

  });

});