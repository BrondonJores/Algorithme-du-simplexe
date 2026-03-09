<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Simplexe Solver</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;600;700&display=swap" rel="stylesheet" />
<link href="style.css" rel="stylesheet" />
</head>
<body>
<canvas id="grid"></canvas>
<div class="overlay" id="symbols-overlay"></div>
<div class="stream" id="data-stream"></div>

<!-- Barre de navigation -->
<nav>
  <h1><i class="fas fa-cube"></i></h1>
  <ul>
    <li><a href="#fonctionnement">Fonctionnement</a></li>
    <li><a href="#exemple">Exemple</a></li>
    <li><a href="solveur.php">Solveur</a></li>
  </ul>
</nav>

<div class="content">
  <!-- Section 1 : Explication -->
<section id="fonctionnement" style="min-height:100vh; display:flex; align-items:center; justify-content:center;">
  <div style="display:flex; width:100%; max-width:1500px; gap:8rem; justify-content:center; align-items:flex-start;">
    
    <!-- Texte explicatif à gauche aligné à gauche -->
    <div style="flex:1; padding:2rem; text-align:left; position:relative; left: -150px;">
      <h2 style="font-size:2.8rem; color:#0ff; text-shadow:0 0 20px rgba(0,255,255,0.5);">Comment ça fonctionne ?</h2>
      <p style="font-size:1.3rem; color:#ccc; line-height:1.6; text-align:left;">
        Notre solveur du simplexe vous permet de résoudre vos problèmes d'optimisation linéaire
        en visualisant chaque étape. Suivez les tableaux pour comprendre le processus,
        du tableau initial à la solution optimale.
      </p>
      <a href="solveur.php" class="btn" style="margin-top:1rem;">Tester maintenant</a>
    </div>
    
    <!-- Trois tableaux empilés à droite, plus grands -->
    <div style="flex:1; position:relative; display:flex; flex-direction:column; align-items:center; justify-content:center; height:100%; text-align:center;">
      
        <!-- Tableau 1 -->
  <table id="tableau1" class="tableau-anim">
    <tr style="background:rgba(0,255,255,0.15);">
      <th>Base</th><th>x1</th><th>x2</th><th>E1</th><th>E2</th><th>E3</th><th>2nd membre</th><th>Ratio</th>
    </tr>
    <tr><td>E1</td><td>1</td><td>1</td><td>1</td><td>0</td><td>0</td><td>4</td><td>4</td></tr>
    <tr><td>E2</td><td>1</td><td>0</td><td>0</td><td>1</td><td>0</td><td>2</td><td>2</td></tr>
    <tr><td>E3</td><td>0</td><td>1</td><td>0</td><td>0</td><td>1</td><td>3</td><td>3</td></tr>
    <tr><td>Z</td><td>3</td><td>2</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td></tr>
  </table>

  <!-- Tableau 2 -->
  <table id="tableau2" class="tableau-anim">
    <tr style="background:rgba(0,255,255,0.15);">
      <th>Base</th><th>x1</th><th>x2</th><th>E1</th><th>E2</th><th>E3</th><th>2nd membre</th><th>Ratio</th>
    </tr>
    <tr><td>x1</td><td>1</td><td>1</td><td>1</td><td>0</td><td>0</td><td>4</td><td>4</td></tr>
    <tr><td>E2</td><td>0</td><td>-1</td><td>-1</td><td>1</td><td>0</td><td>2</td><td>2</td></tr>
    <tr><td>E3</td><td>0</td><td>1</td><td>0</td><td>0</td><td>1</td><td>3</td><td>3</td></tr>
    <tr><td>Z</td><td>0</td><td>1</td><td>1</td><td>0</td><td>0</td><td>12</td><td>-</td></tr>
  </table>

  <!-- Tableau 3 -->
  <table id="tableau3" class="tableau-anim">
    <tr style="background:rgba(0,255,255,0.15);">
      <th>Base</th><th>x1</th><th>x2</th><th>E1</th><th>E2</th><th>E3</th><th>2nd membre</th><th>Ratio</th>
    </tr>
    <tr><td>x1</td><td>1</td><td>0</td><td>0.5</td><td>0</td><td>0</td><td>3</td><td>3</td></tr>
    <tr><td>x2</td><td>0</td><td>1</td><td>0.5</td><td>0</td><td>0</td><td>1</td><td>1</td></tr>
    <tr><td>E3</td><td>0</td><td>0</td><td>-0.5</td><td>0</td><td>1</td><td>2</td><td>2</td></tr>
    <tr><td>Z</td><td>0</td><td>0</td><td>-1.5</td><td>0</td><td>0</td><td>14</td><td>-</td></tr>
  </table>
      
    </div>
    
  </div>
</section>





<!-- Section 2 : Exemple des tableaux -->
<section id="exemple" style="min-height:100vh;">
  <h2 style="text-align:center; color:#0ff; font-size:3rem; margin-bottom:3rem;">Exemple étape par étape</h2>

  <div class="step-container" style="display:flex; flex-direction:column; gap:4rem; min-width:95vw;  margin-right: 10px !important ">

    <!-- Step 0 : Tableau initial -->
    <div class="step" style="display:flex; align-items:center; gap:2rem;">
      <div class="step-number" style="font-size:3rem; color:#0ff;">1</div>
      <div class="step-table" style="flex:1; overflow-x:hidden; wi">
        <table style="border-collapse:collapse; border:2px solid #0ff; background:rgba(0,0,0,0.85); color:#0ff; font-size:1.2rem;">
          <tr style="background:rgba(0,255,255,0.15);">
            <th>Base</th><th>x1</th><th>x2</th><th>E1</th><th>E2</th><th>E3</th><th>2nd membre</th><th>Ratio</th>
          </tr>
          <tr><td>E1</td><td>1</td><td>1</td><td>1</td><td>0</td><td>0</td><td>4</td><td>4</td></tr>
          <tr><td>E2</td><td>1</td><td>0</td><td>0</td><td>1</td><td>0</td><td>2</td><td>2</td></tr>
          <tr><td>E3</td><td>0</td><td>1</td><td>0</td><td>0</td><td>1</td><td>3</td><td>3</td></tr>
          <tr><td>Z</td><td>3</td><td>2</td><td>0</td><td>0</td><td>0</td><td>0</td><td>-</td></tr>
        </table>
      </div>
      <div class="step-desc" style="flex:1; color:#ccc;">
        <strong>Tableau initial :</strong> Toutes les variables d'écart E1, E2, E3 sont en base. La ligne Z montre les coefficients de la fonction objectif.
      </div>
    </div>

    <!-- Step 1 : Pivot x1 -->
    <div class="step" style="display:flex; align-items:center; gap:2rem; flex-direction:row-reverse;">
      <div class="step-number" style="font-size:3rem; color:#0ff;">2</div>
      <div class="step-table" style="flex:1; overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; border:2px solid #0ff; background:rgba(0,0,0,0.85); color:#0ff; font-size:1.2rem;">
          <tr style="background:rgba(0,255,255,0.15);">
            <th>Base</th><th>x1</th><th>x2</th><th>E1</th><th>E2</th><th>E3</th><th>2nd membre</th><th>Ratio</th>
          </tr>
          <tr><td>x1</td><td>1</td><td>0</td><td>0</td><td>1</td><td>0</td><td>2</td><td>-</td></tr>
          <tr><td>E1</td><td>0</td><td>1</td><td>1</td><td>-1</td><td>0</td><td>2</td><td>2</td></tr>
          <tr><td>E3</td><td>0</td><td>1</td><td>0</td><td>0</td><td>1</td><td>3</td><td>3</td></tr>
          <tr><td>Z</td><td>0</td><td>2</td><td>0</td><td>-3</td><td>0</td><td>-6</td><td>-</td></tr>
        </table>
      </div>
      <div class="step-desc" style="flex:1; color:#ccc; text-align:left;">
        <strong>Tableau 1 :</strong> x1 entre dans la base (E2 sort). Le 2nd membre de Z devient négatif après la combinaison linéaire. On voit l'évolution de la fonction économique.
      </div>
    </div>

    <!-- Step 2 : Pivot x2 -->
    <div class="step" style="display:flex; align-items:center; gap:2rem;">
      <div class="step-number" style="font-size:3rem; color:#0ff;">3</div>
      <div class="step-table" style="flex:1; overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; border:2px solid #0ff; background:rgba(0,0,0,0.85); color:#0ff; font-size:1.2rem;">
          <tr style="background:rgba(0,255,255,0.15);">
            <th>Base</th><th>x1</th><th>x2</th><th>E1</th><th>E2</th><th>E3</th><th>2nd membre</th><th>Ratio</th>
          </tr>
          <tr><td>x1</td><td>1</td><td>0</td><td>0</td><td>1</td><td>0</td><td>2</td><td>-</td></tr>
          <tr><td>x2</td><td>0</td><td>1</td><td>1</td><td>-1</td><td>0</td><td>2</td><td>-</td></tr>
          <tr><td>E3</td><td>0</td><td>0</td><td>-1</td><td>1</td><td>1</td><td>1</td><td>-</td></tr>
          <tr><td>Z</td><td>0</td><td>0</td><td>-2</td><td>-1</td><td>0</td><td>-10</td><td>-</td></tr>
        </table>
      </div>
      <div class="step-desc" style="flex:1; color:#ccc;">
        <strong>Tableau 2 :</strong> x2 entre dans la base (E1 sort). Toutes les valeurs de Z sont maintenant ≤ 0, on atteint la solution optimale.
      </div>
    </div>

    <!-- Step 3 : Solution finale -->
    <div class="step" style="display:flex; align-items:center; gap:2rem; flex-direction:row-reverse;">
      <div class="step-number" style="font-size:3rem; color:#0ff;">4</div>
      <div class="step-table" style="flex:1; overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; border:2px solid #0ff; background:rgba(0,0,0,0.85); color:#0ff; font-size:1.2rem;">
          <tr style="background:rgba(0,255,255,0.15);">
            <th>Base</th><th>x1</th><th>x2</th><th>E1</th><th>E2</th><th>E3</th><th>2nd membre</th><th>Ratio</th>
          </tr>
          <tr><td>x1</td><td>1</td><td>0</td><td>0</td><td>1</td><td>0</td><td>2</td><td>-</td></tr>
          <tr><td>x2</td><td>0</td><td>1</td><td>1</td><td>-1</td><td>0</td><td>2</td><td>-</td></tr>
          <tr><td>E3</td><td>0</td><td>0</td><td>-1</td><td>1</td><td>1</td><td>1</td><td>-</td></tr>
          <tr><td>Z</td><td>0</td><td>0</td><td>-2</td><td>-1</td><td>0</td><td>-10</td><td>-</td></tr>
        </table>
      </div>
      <div class="step-desc" style="flex:1; color:#ccc; text-align:left;">
        <strong>Solution finale :</strong> La solution optimale est x1 = 2, x2 = 2, Z = 10. Toutes les valeurs de Z ≤ 0, on s’arrête ici.
      </div>
    </div>

  </div>
</section>

<section id="solution" style="min-height:50vh; display:flex; flex-direction:column; align-items:center; justify-content:center;  color:#0ff; padding:4rem 2rem; text-align:center;">
  <!-- Canvas pour les feux d'artifice -->
  <canvas id="fireworksCanvas2" style="position:absolute; top:0; left:0; width:100%; height:100%; z-index:-1;"></canvas>

  <!-- Icône Font Awesome 3D -->
  <div style="font-size:6rem; color:#0ff; margin-bottom:2rem; animation: bounce 2s infinite;">
    <i class="fas fa-trophy"></i>
  </div>
  
  <h2 style="font-size:3rem; margin-bottom:1rem; color:#0ff;">Solution Optimale Atteinte !</h2>
  
  <p style="font-size:1.5rem; max-width:800px; color:#ccc; line-height:1.6; margin-bottom:2rem;">
    Après avoir appliqué la méthode du simplexe, nous avons trouvé la solution optimale :
  </p>

  <!-- Détails de la solution -->
  <div style="display:flex; flex-direction:row; gap:3rem; flex-wrap:wrap; justify-content:center;">
    <div style="background:rgba(0,255,255,0.05); padding:0rem 1rem; border-radius:15px; box-shadow:0 0 20px rgba(0,255,255,0.3); min-width:150px;">
      <p style="font-size:1.2rem; color:#ccc;">x1 = 2</p>
    </div>
    <div style="background:rgba(0,255,255,0.05); padding:0rem 1rem; border-radius:15px; box-shadow:0 0 20px rgba(0,255,255,0.3); min-width:150px;">
      <p style="font-size:1.2rem; color:#ccc;">x2 = 2</p>
    </div>
    <div style="background:rgba(0,255,255,0.05); padding:0rem 1rem; border-radius:15px; box-shadow:0 0 20px rgba(0,255,255,0.3); min-width:150px; top:-10px;">
      <p style="font-size:1.2rem; color:#ccc;">Zmax = 10</p>
    </div>
  </div>

</section>


</div>

<footer style="text-align:center; margin-bottom:2rem; color:#666; font-size:0.9rem;">
  © 2025 Algorithme du simplexe — Projet académique
</footer>

<script>
// --- Grille 3D ---
const canvas = document.getElementById('grid');
const ctx = canvas.getContext('2d');
canvas.width = window.innerWidth;
canvas.height = window.innerHeight;
let offsetX = 0, offsetY = 0;

function drawGrid() {
  ctx.clearRect(0, 0, canvas.width, canvas.height);
  const step = 40;
  ctx.strokeStyle = 'rgba(0,255,255,0.05)';
  ctx.lineWidth = 1;
  for (let i = 0; i < canvas.width; i += step) {
    ctx.beginPath();
    ctx.moveTo(i + offsetX, 0);
    ctx.lineTo(i, canvas.height);
    ctx.stroke();
  }
  for (let j = 0; j < canvas.height; j += step) {
    ctx.beginPath();
    ctx.moveTo(0, j + offsetY);
    ctx.lineTo(canvas.width, j);
    ctx.stroke();
  }
}

function animateGrid() {
  offsetX += 0.3;
  offsetY += 0.15;
  drawGrid();
  requestAnimationFrame(animateGrid);
}
animateGrid();
window.addEventListener('resize', () => {
  canvas.width = window.innerWidth;
  canvas.height = window.innerHeight;
});

// --- Symboles flottants ---
const overlay = document.getElementById('symbols-overlay');
const symbols = ['x','y','z','π','Σ','λ','∑','θ','μ','δ','∂','∞','α','β','γ','Δ','≤','≥','→','⇔'];
function spawnSymbol() {
  const span = document.createElement('span');
  span.classList.add('symbol');
  span.textContent = symbols[Math.floor(Math.random()*symbols.length)];
  span.style.left = Math.random() * 100 + 'vw';
  span.style.animationDuration = (8 + Math.random()*10) + 's';
  span.style.fontSize = (0.8 + Math.random()*1.2) + 'rem';
  overlay.appendChild(span);
  setTimeout(() => { span.remove(); }, 12000);
}
setInterval(spawnSymbol, 200);

// --- Stream de données ---
const stream = document.getElementById('data-stream');
const dataLines = [];
function generateData() {
  const x1 = (Math.random()*5).toFixed(2), x2 = (Math.random()*5).toFixed(2), Z = (Math.random()*10).toFixed(2);
  dataLines.push(`x1=${x1} x2=${x2} Z=${Z}`);
  if (dataLines.length > 20) dataLines.shift();
  stream.textContent = dataLines.join(' \n');
}
setInterval(generateData, 400);

// --- Parallaxe souris ---
const content = document.querySelector('.content');
document.addEventListener('mousemove', e => {
  const x = e.clientX / window.innerWidth - 0.5;
  const y = e.clientY / window.innerHeight - 0.5;
  content.style.transform = `translate(${x*30}px, ${y*30}px)`;
});

window.addEventListener('load', () => {
  const tableaux = document.querySelectorAll('div[style*="position:relative"] table');
  tableaux.forEach(t => t.classList.add('tableau-anim'));
});

const fireworksCanvas2 = document.getElementById('fireworksCanvas2');
const fwCtx = fireworksCanvas2.getContext('2d');
fireworksCanvas2.width = window.innerWidth;
fireworksCanvas2.height = window.innerHeight;

// Classe feu d'artifice
class Firework2 {
  constructor() { this.reset(); }
  reset() {
    this.x = Math.random() * fireworksCanvas2.width;
    this.y = fireworksCanvas2.height;
    this.targetY = Math.random() * fireworksCanvas2.height / 2;
    this.color = `hsl(${Math.random()*360},100%,50%)`;
    this.particles = [];
    this.exploded = false;
  }
  update() {
    if (!this.exploded) {
      this.y -= 5;
      if (this.y <= this.targetY) this.explode();
    }
    this.particles.forEach(p => {
      p.x += p.vx;
      p.y += p.vy;
      p.alpha -= 0.02;
    });
    this.particles = this.particles.filter(p => p.alpha > 0);
    if (this.exploded && this.particles.length === 0) this.reset();
  }
  explode() {
    this.exploded = true;
    for (let i = 0; i < 30; i++) {
      const angle = Math.random() * Math.PI * 2;
      const speed = Math.random() * 4 + 2;
      this.particles.push({
        x: this.x,
        y: this.y,
        vx: Math.cos(angle) * speed,
        vy: Math.sin(angle) * speed,
        color: this.color,
        alpha: 1
      });
    }
  }
  draw(ctx) {
    if (!this.exploded) {
      ctx.beginPath();
      ctx.arc(this.x, this.y, 3, 0, Math.PI*2);
      ctx.fillStyle = this.color;
      ctx.fill();
    }
    this.particles.forEach(p => {
      ctx.beginPath();
      ctx.arc(p.x, p.y, 2, 0, Math.PI*2);
      ctx.fillStyle = `hsla(${p.color.match(/\d+/g) || 0},100%,50%,${p.alpha})`;
      ctx.fill();
    });
  }
}

// Tableau de feux
const fireworks2 = Array.from({length:7}, () => new Firework2());

// Animation
function animateFireworks2() {
  fwCtx.fillStyle = 'rgba(0,0,0,0.15)';
  fwCtx.fillRect(0,0,fireworksCanvas2.width,fireworksCanvas2.height);
  fireworks2.forEach(f => { f.update(); f.draw(fwCtx); });
  requestAnimationFrame(animateFireworks2);
}
animateFireworks2();

// Ajustement à la taille de la fenêtre
window.addEventListener('resize', () => {
  fireworksCanvas2.width = window.innerWidth;
  fireworksCanvas2.height = window.innerHeight;
});

</script>


</body>
</html>
