const ROWS_PER_PAGE = 5;

let userData = [];
let userCurrentPage = 1;
let postsData = [];
let postsCurrentPage = 1;
let commsData = [];
let commsCurrentPage = 1;

// ====================================== //
//              USERS TABLE               //
// ====================================== //

function pullUserData() {
    fetch('../private/fetch_users.php', {
        method: 'POST',
        headers: {
            'Content-Type' : 'application/x-www-form-urlencoded'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(fetchedData => {
        if (fetchedData.error) {
            console.error('Server error:', fetchedData.error);
        } else {
            userData = fetchedData;
            displayUserTable(userCurrentPage);
            displayUserPagination();
        }
    })
    .catch(error => console.error('Error fetching user data:', error));
}

function displayUserTable(page) {
    const tableBody = document.querySelector('#classTable tbody');
    tableBody.innerHTML = '';

    const start = (page - 1) * ROWS_PER_PAGE;
    const end = start + ROWS_PER_PAGE;
    const paginatedItems = userData.slice(start, end);

    for (const item of paginatedItems) {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${item.id}</td>
            <td>${item.username}</td>
            <td>${item.first_name}</td>
            <td>${item.last_name}</td>
            <td><button class='delete-button' data-id='${item.id}' data-type='user'>Delete</button></td>
        `;
        tableBody.appendChild(row);
    }

    document.querySelectorAll('.delete-button').forEach(button => {
        button.addEventListener('click', handleDelete);
    });
}

function displayUserPagination() {
    const pagination = document.getElementById('userPagination');
    pagination.innerHTML = '';

    const pageCount = Math.ceil(userData.length / ROWS_PER_PAGE);

    for (let i = 1; i <= pageCount; i++) {
        const button = document.createElement('button');
        button.textContent = i;
        button.classList.add(i === userCurrentPage ? 'disabled' : '');
        button.addEventListener('click', () => {
            userCurrentPage = i;
            displayUserTable(userCurrentPage);
            displayUserPagination();
        });
        pagination.appendChild(button);
    }
}

// ====================================== //
//              POSTS TABLE               //
// ====================================== //

function pullPostsData() {
    fetch('../private/fetch_posts.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(fetchedData => {
        if (fetchedData.error) {
            console.error('Server error:', fetchedData.error);
        } else {
            postsData = fetchedData;
            displayPostsTable(postsCurrentPage);
            displayPostsPagination();
        }
    })
    .catch(error => console.error('Error fetching posts data:', error));
}

function displayPostsTable(page) {
    const tableBody = document.querySelector('#classTable2 tbody');
    tableBody.innerHTML = '';

    const start = (page - 1) * ROWS_PER_PAGE;
    const end = start + ROWS_PER_PAGE;
    const paginatedItems = postsData.slice(start, end);

    for (const item of paginatedItems) {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${item.userId}</td>
            <td>${item.title}</td>
            <td>${item.content}</td>
            <td>${item.timestamp}</td>
            <td><button class='delete-button' data-id='${item.id}' data-type='post'>Delete</button></td>
        `;
        tableBody.appendChild(row);
    }

    document.querySelectorAll('.delete-button').forEach(button => {
        button.addEventListener('click', handleDelete);
    });
}

function displayPostsPagination() {
    const pagination = document.getElementById('postsPagination');
    pagination.innerHTML = '';

    const pageCount = Math.ceil(postsData.length / ROWS_PER_PAGE);

    for (let i = 1; i <= pageCount; i++) {
        const button = document.createElement('button');
        button.textContent = i;
        button.classList.add(i === postsCurrentPage ? 'disabled' : '');
        button.addEventListener('click', () => {
            postsCurrentPage = i;
            displayPostsTable(postsCurrentPage);
            displayPostsPagination();
        });
        pagination.appendChild(button);
    }
}

function fetchResults(query) {
    fetch(`../private/search.php?query=${encodeURIComponent(query)}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Network response was not ok: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.error) {
                console.error('Error from server:', data.error);
                renderPostsTable([]);
            } else if (data.message) {
                console.log(data.message);
                renderPostsTable([]); // Clear table if no data found
            } else {
                postsData = data;
                renderPostsTable(postsCurrentPage);
                displayPostsPagination();
            }
        })
        .catch(error => {
            console.error('Error fetching results:', error);
        });
}

function renderPostsTable(page) {
    const tbody = document.querySelector('#classTable2 tbody');
    tbody.innerHTML = '';

    const start = (page - 1) * ROWS_PER_PAGE;
    const end = start + ROWS_PER_PAGE;
    const paginatedItems = postsData.slice(start, end);

    paginatedItems.forEach(row => {
        const tr = document.createElement('tr');
        tr.innerHTML = `<td>${row.userId}</td><td>${row.title}</td><td>${row.content}</td><td>${row.timestamp}</td>`;
        tbody.appendChild(tr);
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

// ====================================== //
//          COMMUNICATIONS TABLE          //
// ====================================== //

function pullCommsData() {
    fetch('../private/fetch_comms.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(fetchedData => {
        if (fetchedData.error) {
            console.error('Server error:', fetchedData.error);
        } else {
            commsData = fetchedData;
            displayCommsTable(commsCurrentPage);
            displayCommsPagination();
        }
    })
    .catch(error => console.error('Error fetching communications data:', error));
}

function displayCommsTable(page) {
    const tableBody = document.querySelector('#classTable3 tbody');
    tableBody.innerHTML = '';

    const start = (page - 1) * ROWS_PER_PAGE;
    const end = start + ROWS_PER_PAGE;
    const paginatedItems = commsData.slice(start, end);

    for (const item of paginatedItems) {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${item.name}</td>
            <td>${item.email}</td>
            <td>${item.message}</td>
            <td>${item.timestamp}</td>
        `;
        tableBody.appendChild(row);
    }
}

function displayCommsPagination() {
    const pagination = document.getElementById('commsPagination');
    pagination.innerHTML = '';

    const pageCount = Math.ceil(commsData.length / ROWS_PER_PAGE);

    for (let i = 1; i <= pageCount; i++) {
        const button = document.createElement('button');
        button.textContent = i;
        button.classList.add(i === commsCurrentPage ? 'disabled' : '');
        button.addEventListener('click', () => {
            commsCurrentPage = i;
            displayCommsTable(commsCurrentPage);
            displayCommsPagination();
        });
        pagination.appendChild(button);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    pullUserData();
    pullPostsData();
    pullCommsData();

    const searchForm = document.getElementById('searchForm');
    searchForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const query = document.getElementById('searchQuery').value;
        fetchResults(query);
    });
});
