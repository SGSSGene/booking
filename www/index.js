let showMonth = {
    year: 2024,
    month: 12
};
fetch(`rest_all_exams.php?year=${showMonth.year}&month=${showMonth.month}`)
  .then((response) => response.json())
  .then((json) => {
    let itemsTag = document.getElementById('items');
    itemsTag.innerHTML = '<tr><th>ID</th><th>Name</th><th>Location</th><th>Starttime</th><th>Duration</th><th>Attendees</th></tr>';


    for (let e of json) {
        itemsTag.innerHTML += `<tr><td>${e.id}</td><td>${e.name}</td><td>${e.location}</td><td>${e.start_time}</td><td>${e.duration}</td><td>${e.attendees}</td></tr>`;
    }
  });
