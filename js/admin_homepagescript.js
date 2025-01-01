// Loading header and footer
function loadHTML(elementId, file) {
    fetch(file)
        .then(response => response.text())
        .then(data => {
            document.getElementById(elementId).innerHTML = data;
        });
}

window.onload = function() {
    loadHTML('header', 'header.html');
    loadHTML('footer', 'footer.html');
};

// Form validation and real-time image preview
document.addEventListener("DOMContentLoaded", function() {
    const form = document.querySelector('form');
    form.addEventListener('submit', function(event) {
        let name = document.getElementById('item-name').value;
        let price = document.getElementById('item-price').value;
        let quantity = document.getElementById('item-quantity').value;

        if (!name || !price || !quantity) {
            alert("Please fill out all required fields.");
            event.preventDefault();
        }
    });
});
// Fetch fruits data dynamically using AJAX
function fetchFruits() {
    fetch('./php/upload_fruits.php')
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          const fruits = data.fruits;
          let tableRows = '';
          fruits.forEach(fruit => {
            tableRows += `
              <tr>
                <td>${fruit.id}</td>
                <td>${fruit.name}</td>
                <td>${fruit.price}</td>
                <td>${fruit.quantity}</td>
                <td>${fruit.category}</td>
                <td>${fruit.rating}</td>
                <td><button class="edit" data-id="${fruit.id}">Edit</button></td>
                <td><button class="delete" data-id="${fruit.id}">Delete</button></td>
              </tr>
            `;
          });
          document.querySelector('#fruits-table tbody').innerHTML = tableRows;
  
          attachEventListeners();
        } else {
          console.error(data.message);
        }
      })
      .catch(error => console.error('Error fetching data:', error));
  }
  
  // Attach event listeners for Edit and Delete buttons
  function attachEventListeners() {
    document.querySelectorAll('.edit').forEach(button => {
      button.addEventListener('click', function() {
        const fruitId = this.getAttribute('data-id');
        alert("Edit button clicked for fruit with ID: " + fruitId);
      });
    });
  
    document.querySelectorAll('.delete').forEach(button => {
      button.addEventListener('click', function() {
        const fruitId = this.getAttribute('data-id');
        if (confirm('Are you sure you want to delete this fruit?')) {
          deleteFruit(fruitId);
        }
      });
    });
  }
  
  // Delete fruit by ID
  function deleteFruit(id) {
    fetch('./php/upload_fruits.php', {
      method: 'POST',
      body: JSON.stringify({ id: id }),
      headers: { 'Content-Type': 'application/json' }
    })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert("Fruit deleted successfully!");
          fetchFruits(); // Refresh the table
        } else {
          alert("Error deleting fruit: " + data.message);
        }
      })
      .catch(error => console.error('Error deleting fruit:', error));
  }
  
  // Initialize
  document.addEventListener("DOMContentLoaded", function() {
    fetchFruits();
  });
  