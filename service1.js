const searchBar = document.getElementById("search-bar");
const list = document.getElementById("list");

const icons = [
    { name: "GitHub" },
    { name: "Grid" },
    { name: "Code" },
    { name: "Chat" },
    { name: "Cloud" },
    { name: "Discord" },
    { name: "Google" },
    { name: "Headphones" },
    { name: "Info" },
    { name: "Key" },
    { name: "Trash" },
    { name: "Twitch" },
    { name: "Type" }
];

const filter = () => {
    list.innerHTML = "";

    const text = searchBar.value.toLowerCase();

    for (let icon of icons) {
        let name = icon.name.toLowerCase();

        if (name.indexOf(text) !== -1) {
            list.innerHTML += `	<div class="d-block mx-1 my-1">
														<li class="text-decoration-none px-3 py-2 d-flex align-items-center rounded">
															<i class="bi bi-${name} me-2"></i> 
															<span> ${icon.name} </span>
														</li>
													</div>`;
        }
    }

    if (list.innerHTML === "") {
        list.innerHTML += `<p> Icon not found. </p>`;
    }
};

filter();

searchBar.addEventListener("keyup", filter);