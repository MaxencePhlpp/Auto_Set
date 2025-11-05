const days = ["Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi"];
const calendar = document.getElementById("calendar");
const weekLabel = document.getElementById("weekLabel");
let currentWeekStart = getMonday(new Date());
const rdvs = []; // stockage local
let selectedRdv = null; // pour édition/suppression

// Couleurs par professionnel
const proColors = {
  "Marc Lefebvre": "#4a90e2",
  "Sarah Martin": "#27ae60",
  "Ahmed Benali": "#d99600"
};

// ==== Initialisation ====
renderCalendar();
updateWeekLabel();

// ==== Navigation de semaine ====
document.getElementById("prevWeek").addEventListener("click", () => {
  currentWeekStart.setDate(currentWeekStart.getDate() - 7);
  renderCalendar();
  updateWeekLabel();
});

document.getElementById("nextWeek").addEventListener("click", () => {
  currentWeekStart.setDate(currentWeekStart.getDate() + 7);
  renderCalendar();
  updateWeekLabel();
});

// ==== Trouver le lundi ====
function getMonday(date) {
  const d = new Date(date);
  const day = d.getDay();
  const diff = d.getDate() - day + (day === 0 ? -6 : 1);
  return new Date(d.setDate(diff));
}

// ==== Afficher calendrier ====
function renderCalendar() {
  calendar.innerHTML = "";

  for (let i = 0; i < 5; i++) {
    const col = document.createElement("div");
    col.classList.add("day-column");

    const header = document.createElement("div");
    header.classList.add("day-header");

    const date = new Date(currentWeekStart);
    date.setDate(date.getDate() + i);

    header.textContent = `${days[i]} ${date.toLocaleDateString("fr-FR", {
      day: "2-digit",
      month: "2-digit",
    })}`;

    col.appendChild(header);
    calendar.appendChild(col);
  }

  for (let r of rdvs) {
    const d = new Date(r.date);
    const monday = getMonday(d);
    if (monday.getTime() === currentWeekStart.getTime()) {
      const dayIndex = (d.getDay() + 6) % 7;
      if (dayIndex <= 4) addRdvToCalendar(dayIndex, r);
    }
  }
}

// ==== Label semaine ====
function updateWeekLabel() {
  const endOfWeek = new Date(currentWeekStart);
  endOfWeek.setDate(endOfWeek.getDate() + 4);
  const numWeek = getWeekNumber(currentWeekStart);
  weekLabel.textContent = `Semaine ${numWeek} (${currentWeekStart.toLocaleDateString("fr-FR")} - ${endOfWeek.toLocaleDateString("fr-FR")})`;
}

function getWeekNumber(date) {
  const d = new Date(Date.UTC(date.getFullYear(), date.getMonth(), date.getDate()));
  const dayNum = d.getUTCDay() || 7;
  d.setUTCDate(d.getUTCDate() + 4 - dayNum);
  const yearStart = new Date(Date.UTC(d.getUTCFullYear(), 0, 1));
  return Math.ceil(((d - yearStart) / 86400000 + 1) / 7);
}

// ==== Ajouter RDV à l'agenda ====
function addRdvToCalendar(dayIndex, r) {
  const column = calendar.children[dayIndex];
  const rdv = document.createElement("div");
  rdv.classList.add("rdv");

  const start = parseInt(r.start.split(":")[0]) + parseInt(r.start.split(":")[1]) / 60;
  const end = parseInt(r.end.split(":")[0]) + parseInt(r.end.split(":")[1]) / 60;
  const totalHours = 18 - 8;
  const topPercent = ((start - 8) / totalHours) * 100;
  const heightPercent = ((end - start) / totalHours) * 100;

  // ✅ Correction ici : toujours un index de base à 0
  const overlapping = rdvs.filter(rr => {
    const d = new Date(rr.date);
    return (
      getMonday(d).getTime() === currentWeekStart.getTime() &&
      (d.getDay() + 6) % 7 === dayIndex &&
      !(rr.end <= r.start || rr.start >= r.end)
    );
  });

  // ✅ Si aucun autre rendez-vous, on garde un index de base = 0
  const index = Math.max(overlapping.findIndex(o => o === r), 0);
  const leftPx = index * 25;

  const color = proColors[r.pro] || "#0a74da";
  rdv.style.top = `${topPercent}%`;
  rdv.style.height = `${heightPercent}%`;
  rdv.style.left = `${leftPx}px`;
  rdv.style.width = `160px`;
  rdv.style.background = `${color}dd`;
  rdv.style.borderLeft = `6px solid ${color}`;

  // ✅ Toujours afficher les infos, même pour le 1er
  rdv.innerHTML = `
    <strong>${r.pro}</strong><br>
    <small>${r.client}</small><br>
    <small>${r.immat}</small><br>
    <small>${r.start} - ${r.end}</small>
  `;

  rdv.addEventListener("click", () => openModal(r));

  column.appendChild(rdv);
}


// ==== Ajout via formulaire ====
document.getElementById("rdvForm").addEventListener("submit", e => {
  e.preventDefault();
  const r = {
    client: client.value,
    immat: immat.value,
    pro: pro.value,
    date: new Date(date.value),
    start: start.value,
    end: end.value
  };
  rdvs.push(r);
  currentWeekStart = getMonday(r.date);
  updateWeekLabel();
  renderCalendar();
  e.target.reset();
});

// ==== Gestion de la modale ====
const modal = document.getElementById("modal");
const editForm = document.getElementById("editForm");

function openModal(r) {
  selectedRdv = r;
  document.getElementById("editClient").value = r.client;
  document.getElementById("editImmat").value = r.immat;
  document.getElementById("editPro").value = r.pro;
  document.getElementById("editDate").value = r.date.toISOString().split("T")[0];
  document.getElementById("editStart").value = r.start;
  document.getElementById("editEnd").value = r.end;
  modal.style.display = "block";
}

document.getElementById("closeModal").addEventListener("click", () => {
  modal.style.display = "none";
});

editForm.addEventListener("submit", e => {
  e.preventDefault();
  if (!selectedRdv) return;
  selectedRdv.client = document.getElementById("editClient").value;
  selectedRdv.immat = document.getElementById("editImmat").value;
  selectedRdv.pro = document.getElementById("editPro").value;
  selectedRdv.date = new Date(document.getElementById("editDate").value);
  selectedRdv.start = document.getElementById("editStart").value;
  selectedRdv.end = document.getElementById("editEnd").value;
  modal.style.display = "none";
  renderCalendar();
});

document.getElementById("deleteRdv").addEventListener("click", () => {
  if (!selectedRdv) return;
  const index = rdvs.indexOf(selectedRdv);
  if (index > -1) rdvs.splice(index, 1);
  modal.style.display = "none";
  renderCalendar();
});
