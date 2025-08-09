// Guard so this only runs on pages that have the filter UI
document.addEventListener("DOMContentLoaded", () => {
  const buttons = document.querySelectorAll(".filter-btn");
  const entries = document.querySelectorAll(".sermon-event-card");
  if (!buttons.length || !entries.length) return;

  const active = { type: [], status: [] };

  const filterEntries = () => {
    entries.forEach((entry) => {
      const entryType = entry.dataset.type;
      const entryStatus = entry.dataset.status;
      const typeMatch = !active.type.length || active.type.includes(entryType);
      const statusMatch = !active.status.length || active.status.includes(entryStatus);
      entry.style.display = typeMatch && statusMatch ? "block" : "none";
    });
  };

  buttons.forEach((btn) => {
    btn.addEventListener("click", () => {
      const filterType = btn.dataset.filter;
      const isActive = btn.classList.contains("active");

      if (isActive) {
        btn.classList.remove("active");
        active.type = active.type.filter((t) => t !== filterType);
        active.status = active.status.filter((s) => s !== filterType);
      } else {
        btn.classList.add("active");
        if (filterType === "events" || filterType === "sermons") {
          if (!active.type.includes(filterType)) active.type.push(filterType);
        } else if (filterType === "upcoming" || filterType === "past") {
          if (!active.status.includes(filterType)) active.status.push(filterType);
        }
      }

      filterEntries();
    });
  });

  filterEntries();
});
