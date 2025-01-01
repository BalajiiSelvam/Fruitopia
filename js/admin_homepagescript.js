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

    // Real-time image preview
    document.getElementById('item-image').addEventListener('change', function(event) {
        let reader = new FileReader();
        reader.onload = function(e) {
            let imgPreview = document.createElement('img');
            imgPreview.src = e.target.result;
            imgPreview.width = 100;
            document.getElementById('image-preview').innerHTML = '';
            document.getElementById('image-preview').appendChild(imgPreview);
        }
        reader.readAsDataURL(event.target.files[0]);
    });

    // Rating system
    const stars = document.querySelectorAll('.rating-star');
    stars.forEach(star => {
        star.addEventListener('click', function() {
            let rating = parseInt(star.getAttribute('data-rating'));
            document.getElementById('item-rating').value = rating;
            stars.forEach(star => {
                star.classList.remove('filled');
            });
            for (let i = 0; i < rating; i++) {
                stars[i].classList.add('filled');
            }
        });
    });
});

// Fetch fruits data dynamically using AJAX
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

      // Add event listeners to the Edit and Delete buttons after the table is populated
      document.querySelectorAll('.edit').forEach(button => {
        button.addEventListener('click', function() {
          const fruitId = this.getAttribute('data-id');
          // Add your edit logic here
          alert("Edit button clicked for fruit with ID: " + fruitId);
          // For example, you can redirect to an edit page or open a modal with the current fruit data
        });
      });

      document.querySelectorAll('.delete').forEach(button => {
        button.addEventListener('click', function() {
          const fruitId = this.getAttribute('data-id');
          // Add your delete logic here
          if (confirm('Are you sure you want to delete this fruit?')) {
            deleteFruit(fruitId); // Call function to delete the fruit
          }
        });
      });
    } else {
      console.error(data.message);
    }
  })
  .catch(error => console.error('Error fetching data:', error));

function deleteFruit(id) {
  fetch('./php/upload_fruits.php', {
    method: 'POST',
    body: JSON.stringify({ id: id }),
    headers: {
      'Content-Type': 'application/json'
    }
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      alert("Fruit deleted successfully!");
      location.reload(); // Reload the page to reflect the changes
    } else {
      alert("Error deleting fruit: " + data.message);
    }
  })
  .catch(error => console.error('Error deleting fruit:', error));
}
