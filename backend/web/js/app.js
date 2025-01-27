window.globalFullCalendarOptions = {
    initialView: "dayGridMonth",
  headerToolbar: {
    left: "prev,next",
    center: "title",
    right: "dayGridMonth,timeGridWeek,timeGridDay",
  },
  themeSystem: "bootstrap5",
  views: {
    dayGridMonth: {
      buttonText: "شهري",
    },
    timeGridWeek: {
      buttonText: "أسبوعي",
    },
    timeGridDay: {
      buttonText: "يومي",
    },
  },
  locale: "ar",
  firstDay: 0,
  weekNumbers: false,
  allDaySlot: false,
  eventOverlap: true,
  slotEventOverlap: true,
  allDayText: "طوال اليوم",
  slotLabelFormat: {
    hour: "numeric",
    minute: "2-digit",
    omitZeroMinute: false,
    meridiem: "short",
  },
  slotDuration: "00:30:00",
  direction: "rtl",
  eventColor: "white",
  eventTextColor: "#fff",
};

$(function () {});

$("#Lswitch_en").click(function () {
  if (!$(".tab-pane.en").hasClass("active")) {
    $("#Lswitch_en").addClass("active");
    $("#Lswitch_ar").removeClass("active");
    $("#Lswitch_fr").removeClass("active");
    $(".tab-pane.en").addClass("active");
    $(".tab-pane.ar").removeClass("active");
    $(".tab-pane.fr").removeClass("active");
  }
});

$("#Lswitch_ar").click(function () {
  if (!$(".tab-pane.ar").hasClass("active")) {
    $("#Lswitch_ar").addClass("active");
    $("#Lswitch_en").removeClass("active");
    $("#Lswitch_fr").removeClass("active");
    $(".tab-pane.ar").addClass("active");
    $(".tab-pane.en").removeClass("active");
    $(".tab-pane.fr").removeClass("active");
  }
});
$("#Lswitch_fr").click(function () {
  if (!$(".tab-pane.fr").hasClass("active")) {
    $("#Lswitch_fr").addClass("active");
    $("#Lswitch_ar").removeClass("active");
    $("#Lswitch_en").removeClass("active");
    $(".tab-pane.fr").addClass("active");
    $(".tab-pane.ar").removeClass("active");
    $(".tab-pane.en").removeClass("active");
  }
});

// ?? ============================= Validation =================================================
reApplyValidation = (form) => {
  // ?? Re-Apply required controls ======== START ========
  // Reapply the `required` attribute after validation
  $(form)
    .find("div")
    .each(function () {
      if ($(this).css("display") === "none") {
        $(this)
          .find("input, select, textarea")
          .each(function () {
            if ($(this).data("required")) {
              $(this).prop("required", true); // Reapply required attribute
            }
          });
      }
    });
  // ?? Re-Apply required controls ======== END ========
};
function validateForm(id, event) {
  if (id) {
    try {
      var form = document.querySelector(id);
      // ?? Check if form has hidden required controls and temporarily disable required attribute
      $(form)
        .find("div")
        .each(function () {
          if ($(this).css("display") === "none") {
            $(this)
              .find("input, select, textarea")
              .each(function () {
                $(this).data("required", $(this).prop("required")); // Store the required state
                $(this).prop("required", false); // Remove required attribute
              });
          }
        });

      //  ?? Custom validation for Select2 elements
      $(form)
        .find("select[required]")
        .each(function () {
          var select2Element = $(this);
          if ($(this).attr("multiple") && $(this).val()?.length === 0) {
            select2Element
              .next()
              .find(".select2-selection")
              .addClass("is-invalid");
            select2Element
              .next()
              .find(".select2-selection")
              .removeClass("is-valid");
          } else if (!$(this).val()) {
            select2Element
              .next()
              .find(".select2-selection")
              .addClass("is-invalid");
            select2Element
              .next()
              .find(".select2-selection")
              .removeClass("is-valid");
          } else {
            select2Element
              .next()
              .find(".select2-selection")
              .removeClass("is-invalid");
            select2Element
              .next()
              .find(".select2-selection")
              .addClass("is-valid");
          }

          // Handle change event to toggle validation classes
          select2Element.on("change", function () {
            if (
              $(this).val() &&
              $(this).val()?.length &&
              $(this).val()?.length > 0
            ) {
              $(this)
                .next()
                .find(".select2-selection")
                .removeClass("is-invalid")
                .addClass("is-valid");
            } else {
              $(this)
                .next()
                .find(".select2-selection")
                .removeClass("is-valid")
                .addClass("is-invalid");
            }
          });
        });

      if (form.checkValidity() === false) {
        event.preventDefault();
        event.stopImmediatePropagation();
        event.stopPropagation();
        form.classList.add("was-validated");
        return false;
      }

      form.classList.add("was-validated");
      if ($(form)?.data("start_spinner")) {
        $("#global_spinner").fadeIn();
      }
      reApplyValidation(form);
      if ($(form)?.attr("action")) {
        form?.submit();
      }
      return true;
    } catch (error) {
      return false;
    }
  }
}
// ?? ============================= Validation =================================================

function setRequiredLabels() {
  $("select:required, input:required, textarea:required").each(function () {
    const id = $(this).attr("id");
    if (id) {
      $(`label[for="${id}"]`).addClass("required");
    }
  });
}

function setupTimePickerClearable() {
  // ?? Set all X icons
  const dtIcons = $(".close_icon");
  setTimeout(() => {
    $(dtIcons).each(function () {
      const that = this;
      if (!$(that).parent().find("input").val()) {
        $(that).fadeOut(0);
      }
      // ?? Sho/hide X ixon when Timepicker changed

      $(that)
        ?.parent()
        ?.find("input")
        ?.on("change", function () {
          if ($(this).val()) {
            $(that).fadeIn(0);
          } else {
            $(that).fadeOut(0);
          }
        });
    });
  }, 100);

  // ?? clear when click icons
  $(".close_icon").on("click", function () {
    const that = this;
    $(that).fadeOut(0);
    $(that).parent().find("input").val(null);
  });
}

function setFancyUtils() {
  $(document).on("beforeShow.fb", function (e, instance, slide) {
    // ?? set fancy title
    var title = slide.opts?.$orig?.data("title");
    if (title) {
      instance.$refs.container?.find(".modal-title")?.html(title);
    }
    // ?? set fancy size
    var size = slide.opts?.$orig?.data("size");
    if (size) {
      instance.$refs.container
        ?.find(".modal-dialog")
        ?.addClass("modal-" + size);
    }

    // ?? ==========
  });
}

function setConfirmModal() {
  document.querySelectorAll("[data-confirm]").forEach(function (element) {
    element.addEventListener("click", function (e) {
      e.preventDefault();
      e.stopImmediatePropagation();
      var message = this.getAttribute("data-confirm");
      var href = this.getAttribute("href");
      var method = this.getAttribute("data-method") || "post";

      Swal.fire({
        title: "تأكيـــد العملية",
        text: message,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "تأكيـــد",
        cancelButtonText: "إلغــاء",
        customClass: {
          confirmButton: "btn btn-danger",
          cancelButton: "btn btn-secondary",
        },
        buttonsStyling: false,
      }).then((result) => {
        if (result.isConfirmed) {
          if (method === "post") {
            $.post(href).done(function () {
              location.reload();
            });
          }
        }
      });
    });
  });
}

function setMenuActiveTree() {
  setTimeout(() => {
    document.querySelectorAll(".nav-link.active").forEach(function (element) {
      for (let i = 0; i < 5; i++) {
        if (element.parentElement.classList.contains("has-treeview")) {
          element.parentElement.classList.add("menu-open");
          break;
        }
        element = element.parentElement;
      }
    });
  }, 10);
}

document.addEventListener("DOMContentLoaded", function () {
  setupTimePickerClearable();
  setRequiredLabels();
  setFancyUtils();
  setConfirmModal();
  setMenuActiveTree();
});

(function () {
  "use strict";
  window.addEventListener(
    "load",
    function () {
      // Fetch all the forms we want to apply custom Bootstrap validation styles to
      var forms = document.getElementsByClassName("needs-validation");
      // Loop over them and prevent submission
      var validation = Array.prototype.filter.call(forms, function (form) {
        form.addEventListener(
          "submit",
          function (event) {
            event.preventDefault();
            event.stopPropagation();
            event.stopImmediatePropagation();
            validateForm("#" + form.id, event);
          },
          false
        );
      });
    },
    false
  );
})();
