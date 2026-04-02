document.addEventListener("click", function (event) {
  var paginationLink = event.target.closest(".pagination a");
  if (!paginationLink) {
    return;
  }

  event.preventDefault();
  var container = document.getElementById("company-list-container");
  if (!container) {
    window.location.href = paginationLink.href;
    return;
  }

  fetch(paginationLink.href)
    .then(function (response) {
      return response.text();
    })
    .then(function (html) {
      var parser = new DOMParser();
      var doc = parser.parseFromString(html, "text/html");
      var newContainer = doc.getElementById("company-list-container");
      if (!newContainer) {
        window.location.href = paginationLink.href;
        return;
      }

      container.innerHTML = newContainer.innerHTML;
      window.history.pushState({ path: paginationLink.href }, "", paginationLink.href);
      window.scrollTo({ top: container.offsetTop - 50, behavior: "smooth" });
    })
    .catch(function () {
      window.location.href = paginationLink.href;
    });
});
