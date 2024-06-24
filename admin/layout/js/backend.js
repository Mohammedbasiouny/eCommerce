$(function () {
  "use strict";

  // Dashboard
  $(".toggle-info").click(function () {
    $(this)
      .toggleClass("selected")
      .parent()
      .next(".panel-body")
      .fadeToggle(100);

    if ($(this).hasClass("selected")) {
      $(this).html('<i class="fa fa-minus fa-lg"></i>');
    } else {
      $(this).html('<i class="fa fa-plus fa-lg"></i>');
    }
  });

  // Trigger The Selectboxit
  $("select").selectBoxIt({
    autoWidth: false,
  });

  // Hide Placeholder On Form Focus
  $("[placeholder]")
    .focus(function () {
      $(this).attr("data-text", $(this).attr("placeholder"));

      $(this).attr("placeholder", "");
    })
    .blur(function () {
      $(this).attr("placeholder", $(this).attr("data-text"));
    });

  // Add Asterisk On Required Field
  $("input").each(function () {
    if ($(this).attr("required") === "required") {
      $(this).after('<span class="asterisk">*</span>');
    }
  });

  // Convert Password Field To Text Field On Hover

  var passField = $(".password");

  $(".show-pass").hover(
    function () {
      passField.attr("type", "text");
    },
    function () {
      passField.attr("type", "password");
    }
  );

  // Confirmation Message On Button

  $(".confirm").click(function () {
    return confirm("Are You Sure?");
  });

  // Category View Option

  $(".cat h3").click(function () {
    $(this).next(".full-view").fadeToggle(200);
  });

  $(".option span").click(function () {
    $(this).addClass("active").siblings("span").removeClass("active");

    if ($(this).data("view") === "full") {
      $(".cat .full-view").fadeIn(200);
    } else {
      $(".cat .full-view").fadeOut(200);
    }
  });

  // Show Delete Button On Child Cats

  $(".child-link").hover(
    function () {
      $(this).find(".show-delete").fadeIn(400);
    },
    function () {
      $(this).find(".show-delete").fadeOut(400);
    }
  );

  // Example JavaScript for avatar upload form
  // Preview selected image before upload
  const avatarInput = document.getElementById("avatar");
  const avatarPreview = document.querySelector(".avatar-preview");

  avatarInput.addEventListener("change", function () {
    const file = this.files[0];

    if (file) {
      const reader = new FileReader();

      reader.onload = function (e) {
        avatarPreview.src = e.target.result;
      };

      reader.readAsDataURL(file);
    }
  });

  // Optional: Add client-side validation for file type and size
  avatarInput.addEventListener("change", function () {
    const file = this.files[0];
    const allowedTypes = ["image/jpeg", "image/png", "image/gif"];
    const maxSize = 5 * 1024 * 1024; // 5MB

    if (!allowedTypes.includes(file.type)) {
      alert("Only JPG, PNG, and GIF files are allowed.");
      this.value = ""; // Clear the input
    } else if (file.size > maxSize) {
      alert("File size must be less than 5MB.");
      this.value = ""; // Clear the input
    }
  });
});
