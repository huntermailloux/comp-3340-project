function fetchResults(query) {
    fetch(`../private/search.php?query=${encodeURIComponent(query)}`)
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                console.log('Fetch error:', response.status, response.statusText);
                throw new Error(`Network response was not ok: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.error) {
                console.error('Error from server:', data.error);
                renderTable([]);
            } else if (data.message) {
                console.log(data.message);
                renderTable([]);
            } else {
                renderTable(data);
            }
        })
        .catch(error => {
            console.error('Error fetching results:', error);
        });
}

function renderTable(data) {
    const tbody = document.querySelector('#classTable2 tbody');
    tbody.innerHTML = '';

    data.forEach(row => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${row.userId}</td>
            <td>${row.title}</td>
            <td>${row.content}</td>
            <td>${row.timestamp}</td>
            <td><button class='delete-button' data-id='${row.id}' data-type='post'>Delete</button></td>
        `;
        tbody.appendChild(tr);
    });

    document.querySelectorAll('.delete-button').forEach(button => {
        button.addEventListener('click', handleDelete);
    });
}

function handleDelete(event) {
    const id = event.target.dataset.id;
    const type = event.target.dataset.type;
    let url = '';

    if (type === 'user') {
        url = `../private/admin_delete_user.php?id=${id}`;
    } else if (type === 'post') {
        url = `../private/admin_delete_post.php?id=${id}`;
    }

    if (confirm('Are you sure you want to delete this record?')) {
        fetch(url, {
            method: 'DELETE'
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw new Error(err.error); });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert('Record deleted successfully');
                fetchResults(document.getElementById('searchQuery').value);
            } else {
                alert(`Error deleting record: ${data.error}` );
            }
        })
        .catch(error => {
            console.error('Error deleting record:', error.message);
        });
    }
}

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('searchForm').addEventListener('submit', function(e) {
        e.preventDefault();
        let query = document.getElementById('searchQuery').value;
        fetchResults(query);
    });
});
