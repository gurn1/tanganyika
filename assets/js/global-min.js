function raven_main_navigation($){var e=$("#site-navigation"),a=$(".menu-toggle"),s=$(".menu-item-toggle"),i="300",n=e.is(":visible");a.on("click",(function(a){var s=e.is(":visible"),n=$(this).find("i");e.slideUp(i),n.removeClass("fa-times"),s||(e.slideDown(i),n.addClass("fa-times")),a.stopPropagation()})),n||$(document).on("click touchstart",(function(){e.slideUp(i),a.find("i").removeClass("fa-times")})),e.on("click touchstart",(function(e){e.stopPropagation()})),s.on("click",(function(){var e=$(".sub-menu"),a=$(this).parent().find(".sub-menu"),n=a.is(":visible"),t=$(this).find("i"),o=s.find("i");e.slideUp(i),o.removeClass("fa-minus-square").addClass("fa-plus-square"),n||(a.slideDown(i),t.addClass("fa-minus-square").removeClass("fa-plus-square"))}))}function raven_search_bar($){var e=$(".search-container"),a=$(".search-toggle"),s="300",i=e.is(":visible");a.on("click",(function(a){var i=e.is(":visible"),n=$(this).find("i");e.slideUp(s),n.removeClass("fa-times"),i||(e.slideDown(s),n.addClass("fa-times")),a.stopPropagation()})),e.on("click touchstart",(function(e){e.stopPropagation()})),i||$(document).on("click touchstart",(function(){e.slideUp(s),$("#site-navigation").slideUp(s),a.find("i").removeClass("fa-times"),$(".menu-toggle").find("i").removeClass("fa-times")}))}function raven_widget_navigation($){var e=$(".child-terms"),a=($("#product_categories_list"),$(".category-icon")),s=$(".has-children"),i=$("#product_categories_list > li > a .category-icon");s.find(a).on("click",(function(a){a.preventDefault();var s=$(this).parent().parent().find(".child-terms"),n=$(this).parent().find("> .category-icon > i"),t=s.is(":visible");e.slideUp($speed),i.find("i").removeClass("fa-minus-square").addClass("fa-plus-square"),t||(s.slideDown($speed),n.removeClass("fa-plus-square").addClass("fa-minus-square"))}))}function raven_primary_contact_form($){var e="#raven_primary_contact_form",a=".notice-container";$(e).on("click",".button",(function(s){s.preventDefault();var i=$(this).closest(e).find('input[name="_wpnonce"]').val(),n=$(this),t=$(this).find(".icon-box").html(),o=$(this).closest(e).serialize();if(1==raven_validate($,e))return!1;raven_submit_animations($,e,"add",t),$.ajax({type:"POST",url:ravenObject.ajaxurl,data:{action:"submit_primary_form",security:i,form_data:o},success:function(s){if(null!=s&&""!=s&&0!=s&&s.length>0){s=$.parseJSON(s);$(a).append().html(s.html),$("html, body").animate({scrollTop:$(a).offset().top-50},800),n.find(".icon-box").html('<i class="far fa-hourglass"></i>'),setTimeout((function(){raven_submit_animations($,e,"remove",t,s.response)}),1800)}}})}))}function raven_validate($,e){var a=$(e).find("input,textarea,select").filter("[required]:visible"),s=($(e).find(":submit"),!1),i=!0;return $(e).find(".field > .error").remove(),a&&a.each((function(){if("email"==$(this).attr("type")&&(i=raven_validate_email_address($(this).val())),""==$(this).val()||"Please Select"==$(this).val()||0==i)return $("html, body").stop().animate({scrollTop:$(this).offset().top-80},800),$(this).focus(),0!=i?$(this).parent().append('<span class="error">This is a required field</span>'):$(this).parent().append('<span class="error">Please enter an email address</span>'),s=!0,!1})),s}function raven_conditional_fields($){var e=".conditional";$(e+' input[type="radio"]').on("change",(function(){var a=$(this).val(),s=$(this).closest(e);s.find(".condition").removeClass("visible"),s.find('[data-option="'+a+'"]').addClass("visible")}))}function raven_submit_animations($,e,a,s,i){var n=$(e).find("input,textarea,select").filter(":visible"),t=$(e).find(":submit");n&&n.each((function(){"add"==a?$(this).attr("disabled",!0):"remove"==a&&($(this).attr("disabled",!1),"sent"==i&&($(this).val(""),($(this).is(":radio")||$(this).is(":checkbox"))&&$(this).prop("checked",!1)))})),"remove"==a&&$(".condition").removeClass("visible"),"add"==a?(t.find(".icon-box").html('<i class="fas fa-spinner fa-pulse"></i>'),t.attr("disabled",!0)):"remove"==a&&(t.find(".icon-box").html(s),t.attr("disabled",!1))}function raven_validate_email_address(e){return/^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i.test(e)}function raven_password_renewal_checker($){let e=$('.woocommerce-Input[name="username"]'),a=$(".woocommerce-form-login__submit");e.on("focusin",(function(){let e=this.offsetHeight/2;a.prop("disabled",!0).addClass("disabled"),$(".spinner").length||$(this).closest(".form-row").append(`<span class="spinner" style="top: auto; bottom: ${e}px"><i class="fas fa-spinner fa-spin"></i></span>`)})),e.on("focusout",(function(){let e=$(this),s=e.val();s.length&&isEmail(s)?$.ajax({type:"POST",url:ravenObject.ajaxurl,data:{action:"password_renewal_checker",emailAddress:s},success:function(i){1!=i?$(".password-update-required").length||($(".woocommerce-form-message").append(`<span class="woocommerce-error password-update-required"><span class="error-header">Password Update Required</span>Please follow the link to change your password. <a href="${ravenObject.lostpwordURL}?email=${s}">Update Password</a></span>`),console.log(e.closest(".woocommerce-form-message"))):a.prop("disabled",!1).removeClass("disabled"),e.closest(".form-row").find(".spinner").remove()},error:function(e,a){error_report(e,a)}}):(a.prop("disabled",!1).removeClass("disabled"),$(this).closest(".form-row").find(".spinner").remove())}))}function isEmail(e){return/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(e)}function error_report(e,a){0===e.status?console.log("Not connect.\n Verify Network."):404==e.status?console.log("Requested page not found. [404]"):500==e.status?console.log("Internal Server Error [500]."):"parsererror"===a?console.log("Requested JSON parse failed."):"timeout"===a?console.log("Time out error."):"abort"===a?console.log("Ajax request aborted."):console.log("Uncaught Error.\n"+e.responseText)}!function($){$(document).ready((function(){raven_main_navigation($),raven_search_bar($),raven_password_renewal_checker($),raven_primary_contact_form($),raven_conditional_fields($)}))}(jQuery);