document.addEventListener("livewire:navigated", () => {
	let rightMenu = localStorage.getItem("rightMenu");
	const rightMenuEl = document.getElementById("right-menu")!;
	const rightMenuSwitch: HTMLElement = document.getElementById("right-menu-btn")!;

	function closeMenu(): void {
		rightMenuEl.classList.add("closed");
		localStorage.setItem("rightMenu", "closed");
		rightMenu = "closed";
	}

	function openMenu(): void {
		rightMenuEl!.classList.remove("closed");
		localStorage.setItem("rightMenu", "");
		rightMenu = "";
	}

	if (rightMenu === "closed") closeMenu();

	rightMenuSwitch.addEventListener("click", () => {
		rightMenuEl.style.transition = "0.2s";

		if (rightMenu === "closed") openMenu();
		else closeMenu();
	});
});
