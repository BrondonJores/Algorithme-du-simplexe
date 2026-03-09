<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Simplexe Solver</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;600;700&display=swap" rel="stylesheet" />
<link href="style.css" rel="stylesheet" />

</head>
<body>

<!-- Barre de navigation -->
<nav>
  <h1><i class="fas fa-cube"></i></h1>
  <ul>
    <li><a href="index.php#fonctionnement">Fonctionnement</a></li>
    <li><a href="index.php#exemple">Exemple</a></li>
    <li><a href="solveur.php">Solveur</a></li>
  </ul>
</nav>

<<section id="solveur">
  <!-- Partie gauche : Configuration -->
  <div class="config">
    <h2>Configuration</h2>
    <form id="configForm">
      <label>Nombre de variables : <input type="number" id="numVars" min="1" max="20" required></label>
      <label>Nombre de contraintes : <input type="number" id="numConstr" min="1" max="20" required></label>
      <label>Type d'optimisation : 
        <select id="optType">
          <option value="max">Maximisation</option>
          <option value="min">Minimisation</option>
        </select>
      </label>
      <button type="submit">Générer le formulaire</button>
    </form>
  </div>

  <!-- Partie droite : Formulaire des équations -->
  <div class="equations" id="equationsForm">
    <h2>Formulaire des équations</h2>
    <p>Remplissez la configuration à gauche pour générer les champs ici.</p>
  </div>
</section>

<section id="resultats">
  <h2>Résultats étape par étape</h2>
  <div id="resultContainer" class="step-container" style="display:flex; flex-direction:column; gap:4rem; min-width:95vw;  margin-right: 10px !important ">
    <p style="text-align:center; color:#ccc;">Les résultats apparaîtront ici après exécution du solveur.</p>
  </div>
  
  
</section>



<script>
// === Génération dynamique du formulaire ===
document.getElementById('configForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const numVars = Math.min(parseInt(document.getElementById('numVars').value), 20);
    const numConstr = Math.min(parseInt(document.getElementById('numConstr').value), 20);
    const optType = document.getElementById('optType').value;

    const container = document.getElementById('equationsForm');
    container.innerHTML = '';

    const form = document.createElement('form');
    form.id = 'solverForm';
    form.method = 'POST';
    form.action = 'algo_simplexe.php';

    // Champs cachés pour PHP
    ['numVars','numConstr','optType'].forEach(name => {
        const hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.name = name;
        hidden.value = eval(name);
        form.appendChild(hidden);
    });

    // Fonction économique
    const objTitle = document.createElement('h3');
    objTitle.textContent = 'Fonction objectif';
    form.appendChild(objTitle);

    const objDiv = document.createElement('div');
    for(let i=1; i<=numVars; i++){
        const input = document.createElement('input');
        input.type = 'number';
        input.name = `obj_x${i}`;
        input.required = true;
        objDiv.appendChild(input);

        const span = document.createElement('span');
        span.textContent = `x${i} ${i<numVars?'+':''}`;
        objDiv.appendChild(span);
    }
    form.appendChild(objDiv);

    // Contraintes
    const constrTitle = document.createElement('h3');
    constrTitle.textContent = 'Contraintes';
    form.appendChild(constrTitle);

    for(let j=1; j<=numConstr; j++){
        const constrDiv = document.createElement('div');

        for(let i=1; i<=numVars; i++){
            const input = document.createElement('input');
            input.type = 'number';
            input.name = `c${j}${i}`;
            input.required = true;
            constrDiv.appendChild(input);

            const span = document.createElement('span');
            span.textContent = `x${i} ${i<numVars?'+':''}`;
            constrDiv.appendChild(span);
        }

        // Label simple ≤
        const ineqLabel = document.createElement('span');
        ineqLabel.textContent = '≤';
        ineqLabel.style.margin = '0 0.3rem';
        constrDiv.appendChild(ineqLabel);

        const rhsInput = document.createElement('input');
        rhsInput.type = 'number';
        rhsInput.name = `rhs${j}`;
        rhsInput.required = true;
        constrDiv.appendChild(rhsInput);

        form.appendChild(constrDiv);
    }

    // Bouton soumission
    const submit = document.createElement('button');
    submit.type = 'submit';
    submit.textContent = 'Résoudre le problème';
    form.appendChild(submit);

    container.appendChild(form);
});

// === Génération d'un tableau HTML à partir d'un tableau 2D ===
function generateTableHTML(array2D){
    let html = '<table>';
    array2D.forEach((row,i) => {
        html += '<tr>';
        row.forEach(cell => {
            html += i===0 ? `<th>${cell}</th>` : `<td>${cell}</td>`;
        });
        html += '</tr>';
    });
    html += '</table>';
    return html;
}
// === Fonction pour exécuter le solveur ===
async function runSimplexe(formData){
    const container = document.getElementById('resultContainer');
    container.innerHTML = '<p style="text-align:center; color:#0ff;">Calcul en cours...</p>';

    let data;
    try {
        const response = await fetch('algo_simplexe.php', { method:'POST', body: formData });
        data = await response.json();
    } catch(err) {
        container.innerHTML = `<p style="text-align:center; color:red;">Erreur lors de l’exécution : ${err}</p>`;
        return;
    }

    container.innerHTML = '';

    // Affichage étape par étape
    data.steps.forEach((step, index) => {
        const stepDiv = document.createElement('div');
        stepDiv.classList.add('step');

        const numDiv = document.createElement('div');
        numDiv.classList.add('step-number');
        numDiv.textContent = index+1;

        const tableDiv = document.createElement('div');
        tableDiv.classList.add('step-table');
        tableDiv.innerHTML = generateTableHTML(step.table);

        const descDiv = document.createElement('div');
        descDiv.classList.add('step-desc');
        descDiv.textContent = step.desc;

        stepDiv.appendChild(numDiv);
        stepDiv.appendChild(tableDiv);
        stepDiv.appendChild(descDiv);

        container.appendChild(stepDiv);
    });

    // === Bloc solution stylé ===
    if(data.solution){
        // Supprime ancien bloc si existant
        const oldSection = document.getElementById('solution');
        if(oldSection) oldSection.remove();

        // Récupération des valeurs
        const sol = data.solution;
        const vars = Object.entries(sol)
            .filter(([k]) => k.startsWith('x'))
            .map(([k,v]) => `<div style="background:rgba(0,255,255,0.05); padding:0.5rem 1rem; border-radius:15px; box-shadow:0 0 20px rgba(0,255,255,0.3); min-width:150px;">
                                <p style="font-size:1.2rem; color:#ccc;">${k} = ${v}</p>
                              </div>`)
            .join('');
        
        const Z = sol.Z || sol.Z || 0;

        // Création de la section complète
        const solSection = document.createElement('section');
        solSection.id = 'solution';
        solSection.style = "min-height:50vh; display:flex; flex-direction:column; align-items:center; justify-content:center; color:#0ff; padding:4rem 2rem; text-align:center; position:relative;";

        solSection.innerHTML = `
          <canvas id="fireworksCanvas2" style="position:absolute; top:0; left:0; width:100%; height:100%; z-index:-1;"></canvas>
          <div style="font-size:6rem; color:#0ff; margin-bottom:2rem; animation: bounce 2s infinite;">
            <i class="fas fa-trophy"></i>
          </div>
          <h2 style="font-size:3rem; margin-bottom:1rem; color:#0ff;">Solution Optimale Atteinte !</h2>
          <p style="font-size:1.5rem; max-width:800px; color:#ccc; line-height:1.6; margin-bottom:2rem;">
            Après avoir appliqué la méthode du simplexe, nous avons trouvé la solution optimale :
          </p>
          <div style="display:flex; flex-direction:row; gap:3rem; flex-wrap:wrap; justify-content:center;">
            ${vars}
            <div style="background:rgba(0,255,255,0.05); padding:0.5rem 1rem; border-radius:15px; box-shadow:0 0 20px rgba(0,255,255,0.3); min-width:150px;">
              <p style="font-size:1.2rem; color:#ccc;">Z = ${Z}</p>
            </div>
          </div>
        `;

        document.body.appendChild(solSection);
        solSection.scrollIntoView({ behavior: 'smooth' });

        // === Lancer les feux d'artifice ===
        startFireworks('fireworksCanvas2');
    }
}

// === Soumission du solverForm ===
document.addEventListener('submit', async function(e){
    if(e.target.id === 'solverForm'){
        e.preventDefault();
        const formData = new FormData(e.target);
        await runSimplexe(formData);
    }
});

// === Fonction d'effet feu d’artifice (simple canvas) ===
function startFireworks(canvasId){
    const canvas = document.getElementById(canvasId);
    if(!canvas) return;
    const ctx = canvas.getContext('2d');

    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;

    const particles = [];

    function random(min, max){ return Math.random() * (max - min) + min; }

    // Création d’une explosion à une position aléatoire
    function createExplosion(){
        const x = random(canvas.width * 0.2, canvas.width * 0.8);
        const y = random(canvas.height * 0.2, canvas.height * 0.6);
        const colorHue = random(0, 360);
        for(let i=0;i<80;i++){
            particles.push({
                x, y,
                radius: random(2,4),
                color: `hsl(${colorHue},100%,50%)`,
                speed: random(2,7),
                angle: random(0,2*Math.PI),
                life: random(60,120)
            });
        }
    }

    // Lancer une explosion toutes les 700ms
    const interval = setInterval(createExplosion, 10000);

    function draw(){
        ctx.fillStyle = 'rgba(0,0,0,0.2)';
        ctx.fillRect(0,0,canvas.width,canvas.height);

        particles.forEach(p=>{
            ctx.beginPath();
            ctx.arc(p.x,p.y,p.radius,0,2*Math.PI);
            ctx.fillStyle=p.color;
            ctx.fill();
            p.x += Math.cos(p.angle)*p.speed;
            p.y += Math.sin(p.angle)*p.speed;
            p.life--;
        });

        // Supprimer les particules mortes
        for(let i=particles.length-1;i>=0;i--){
            if(particles[i].life<=0) particles.splice(i,1);
        }

        requestAnimationFrame(draw);
    }
    draw();

    // Arrêt automatique après 10 secondes (tu peux augmenter)
    setTimeout(()=>{
        clearInterval(interval);
    }, 10000);
}
</script>

</body>
</html>
