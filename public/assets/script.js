(function () {
  "use strict";

  var CONSENT_KEY = "lems_cookie_consent";
  var CONSENT_TTL_DAYS = 180;
  var isInitialized = false;
  var lems = window.LEMS || {};

  function onReady(callback) {
    if (document.readyState === "loading") {
      document.addEventListener("DOMContentLoaded", callback);
    } else {
      callback();
    }
  }

  function getDialog(dialogId) {
    if (!dialogId) {
      return null;
    }

    return document.getElementById(dialogId);
  }

  function openDialog(dialogId) {
    var dialog = getDialog(dialogId);
    if (dialog && typeof dialog.showModal === "function") {
      dialog.showModal();
    }
  }

  function closeDialog(dialogId) {
    var dialog = getDialog(dialogId);
    if (dialog && typeof dialog.close === "function") {
      dialog.close();
    }
  }

  function bindDialogBackdropClose(dialogIds) {
    Array.prototype.forEach.call(dialogIds || [], function (dialogId) {
      var dialog = getDialog(dialogId);
      if (!dialog || dialog.dataset.lemsBackdropBound === "true") {
        return;
      }

      dialog.dataset.lemsBackdropBound = "true";
      dialog.addEventListener("click", function (event) {
        var rect = dialog.getBoundingClientRect();
        var clickedOutside = event.clientX < rect.left || event.clientX > rect.right || event.clientY < rect.top || event.clientY > rect.bottom;
        if (clickedOutside) {
          dialog.close();
        }
      });
    });
  }

  function setSelectValue(selectElement, value) {
    if (!selectElement) {
      return;
    }

    Array.prototype.forEach.call(selectElement.options, function (option) {
      option.selected = option.value === value;
    });
  }

  lems.onReady = onReady;
  lems.openDialog = openDialog;
  lems.closeDialog = closeDialog;
  lems.bindDialogBackdropClose = bindDialogBackdropClose;
  lems.setSelectValue = setSelectValue;
  window.LEMS = lems;

  function setCookie(name, value, days) {
    var maxAge = days * 24 * 60 * 60;
    document.cookie = name + "=" + encodeURIComponent(value) + ";path=/;max-age=" + maxAge + ";SameSite=Lax";
  }

  function escapeRegExp(value) {
    return value.replace(/[.*+?^${}()|[\]\\]/g, "\\$&");
  }

  function getCookie(name) {
    var pattern = new RegExp("(?:^|; )" + escapeRegExp(name) + "=([^;]*)");
    var match = document.cookie.match(pattern);
    return match ? decodeURIComponent(match[1]) : null;
  }

  function readConsent() {
    try {
      var storedValue = localStorage.getItem(CONSENT_KEY);
      if (storedValue === "accepted" || storedValue === "refused") {
        return storedValue;
      }
    } catch (error) {
      // localStorage can be blocked in some browsers; cookie fallback is used.
    }

    var cookieValue = getCookie(CONSENT_KEY);
    if (cookieValue === "accepted" || cookieValue === "refused") {
      return cookieValue;
    }

    return null;
  }

  function persistConsent(value) {
    try {
      localStorage.setItem(CONSENT_KEY, value);
    } catch (error) {
      // Ignore storage write errors and keep cookie persistence.
    }

    setCookie(CONSENT_KEY, value, CONSENT_TTL_DAYS);
  }

  function initCookieConsent() {
    if (isInitialized) {
      return;
    }

    var banner = document.getElementById("cookie-consent");
    if (!banner) {
      return;
    }

    isInitialized = true;

    var settingsTriggers = document.querySelectorAll("[data-open-cookie-settings]");

    function openBanner() {
      banner.hidden = false;
      var primaryButton = banner.querySelector("[data-cookie-choice='accepted']");
      if (primaryButton instanceof HTMLElement) {
        primaryButton.focus();
      }
    }

    window.openCookieConsent = openBanner;

    Array.prototype.forEach.call(settingsTriggers, function (trigger) {
      trigger.addEventListener("click", function (event) {
        event.preventDefault();
        openBanner();
      });
    });

    var consent = readConsent();
    if (consent) {
      banner.hidden = true;
    } else {
      openBanner();
    }

    banner.addEventListener("click", function (event) {
      var target = event.target;
      if (!(target instanceof HTMLElement)) {
        return;
      }

      var choice = target.getAttribute("data-cookie-choice");
      if (choice !== "accepted" && choice !== "refused") {
        return;
      }

      persistConsent(choice);
      banner.hidden = true;
    });
  }

  onReady(initCookieConsent);
})();
