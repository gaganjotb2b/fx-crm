$(document).ready(function() {
    $("#profile").show();
    $("#basic-info").hide();
    $("#password").hide();
    $("#2fa").hide();
    $("#accounts").hide();
    $("#notifications").hide();
    $("#sessions").hide();
    $("#delete").hide();
    $(document).on("click","#profile-btn",function () {
      $("#profile").show();
      $("#basic-info").hide();
      $("#password").hide();
      $("#2fa").hide();
      $("#accounts").hide();
      $("#notifications").hide();
      $("#sessions").hide();
      $("#delete").hide();
    });
    $(document).on("click","#basic-info-btn",function () {
      $("#profile").hide();
      $("#basic-info").show();
      $("#password").hide();
      $("#2fa").hide();
      $("#accounts").hide();
      $("#notifications").hide();
      $("#sessions").hide();
      $("#delete").hide();
    });

    $(document).on("click","#password-btn",function () {
      $("#profile").hide();
      $("#basic-info").hide();
      $("#password").show();
      $("#2fa").hide();
      $("#accounts").hide();
      $("#notifications").hide();
      $("#sessions").hide();
      $("#delete").hide();
    });
    $(document).on("click","#2fa-btn",function () {
      $("#profile").hide();
      $("#basic-info").hide();
      $("#password").hide();
      $("#2fa").show();
      $("#accounts").hide();
      $("#notifications").hide();
      $("#sessions").hide();
      $("#delete").hide();
    });
    $(document).on("click","#accounts-btn",function () {
      $("#profile").hide();
      $("#basic-info").hide();
      $("#password").hide();
      $("#2fa").hide();
      $("#accounts").show();
      $("#notifications").hide();
      $("#sessions").hide();
      $("#delete").hide();
    });
    $(document).on("click","#notifications-btn",function () {
      $("#profile").hide();
      $("#basic-info").hide();
      $("#password").hide();
      $("#2fa").hide();
      $("#accounts").hide();
      $("#notifications").show();
      $("#sessions").hide();
      $("#delete").hide();
    });
    $(document).on("click","#sessions-btn",function () {
      $("#profile").hide();
      $("#basic-info").hide();
      $("#password").hide();
      $("#2fa").hide();
      $("#accounts").hide();
      $("#notifications").hide();
      $("#sessions").show();
      $("#delete").hide();
    });
    $(document).on("click","#delete-btn",function () {
      $("#profile").hide();
      $("#basic-info").hide();
      $("#password").hide();
      $("#2fa").hide();
      $("#accounts").hide();
      $("#notifications").hide();
      $("#sessions").hide();
      $("#delete").show();
    });
});