const vendorItem = document.querySelectorAll(".vendor-sidenav-item");
const x = window.location.href.split("/");
const pageHref = x[x.length - 1];

vendorItem.forEach(element => {
  if (element.getAttribute("href") == pageHref) {
    element.classList.add("active");
  }
});