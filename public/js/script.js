const modal = document.getElementById("filterModal");

function openFilters() {
  modal.classList.add("is-open");
  modal.setAttribute("aria-hidden", "false");
  document.body.classList.add("modal-open");
}

function closeFilters() {
  modal.classList.remove("is-open");
  modal.setAttribute("aria-hidden", "true");
  document.body.classList.remove("modal-open");
}

document.getElementById("openFilters").addEventListener("click", openFilters);

document.getElementById("closeFilters").addEventListener("click", closeFilters);

document
  .getElementById("dismissFilters")
  .addEventListener("click", closeFilters);

document.addEventListener("keydown", (event) => {
  if (event.key === "Escape" && modal.classList.contains("is-open")) {
    closeFilters();
  }
});
