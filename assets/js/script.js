document.addEventListener("DOMContentLoaded", function () {
  //Search
  const searchInput = document.getElementById("searchInput");
  const tableRows = document.querySelectorAll("#userTable tbody tr");

  searchInput.addEventListener("keyup", function () {
    let filter = searchInput.value.toLowerCase();

    tableRows.forEach((row) => {
      let name = row.cells[1].innerText.toLowerCase();
      let email = row.cells[2].innerText.toLowerCase();

      if (name.includes(filter) || email.includes(filter)) {
        row.style.display = "";
      } else {
        row.style.display = "none";
      }
    });
  });

  //Delete
  const deleteLinks = document.querySelectorAll(".delete-link");

  deleteLinks.forEach((link) => {
    link.addEventListener("click", function (event) {
      let confirmDelete = confirm("Are you sure you want to delete this user?");
      if (!confirmDelete) {
        event.preventDefault();
      }
    });
  });
});
