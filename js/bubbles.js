class Bubble {
  constructor(ctx, canvasWidth, canvasHeight) {
    this.canvasHeight = canvasHeight;
    this.canvasWidth = canvasWidth;
    this.ctx = ctx;
    this.radius = this.randomVal(5, 20);
    this.x = this.randomVal(0, canvasWidth);
    this.y = canvasHeight + this.radius;
    this.velocityX = 1;
    this.velocityY = 2 + this.radius / 10;
    this.active = true;
  }

  draw() {
    const gradient = this.ctx.createRadialGradient(
      this.x,
      this.y - this.radius / 20 * this.radius,
      0,
      this.x,
      this.y - this.radius / 20 * this.radius,
      this.radius
    );
    gradient.addColorStop(0, "rgba(186, 219, 245, .4)");
    gradient.addColorStop(1, "rgba(186, 219, 245, .05)");

    this.ctx.fillStyle = gradient;
    this.ctx.strokeStyle = "rgba(186, 219, 245, .1)";
    this.ctx.beginPath();
    this.ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
    this.ctx.closePath();
    this.ctx.stroke();
    this.ctx.fill();
  }

  inBounds() {
    return (
      this.x >= 0 &&
      this.x <= this.canvasWidth &&
      this.y >= 0 &&
      this.y <= this.canvasHeight + this.radius
    );
  }

  randomVal(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
  }

  update() {
    this.active = this.inBounds();
    this.x += Math.random() < 0.7 ? -this.velocityX : this.velocityX;
    this.y -= this.velocityY;

    this.draw();
  }
}

let bubbles = [];
const canvas = document.createElement("canvas");
const ctx = canvas.getContext("2d");

canvasInit();

function canvasInit() {
  canvas.width = window.innerWidth;
  canvas.height = window.innerHeight;
  document.body.appendChild(canvas);

  canvasDraw();
  window.setInterval(() => canvasAddBubbles(), 500);
}

function canvasAddBubbles() {
  const bubble = new Bubble(ctx, canvas.width, canvas.height);

  bubble.draw();

  bubbles.push(bubble);
}

function canvasDraw() {
  const gradient = ctx.createLinearGradient(0, 0, 0, canvas.height);

  ctx.clearRect(0, 0, canvas.width, canvas.height);

  gradient.addColorStop(0, "#41b5e2");
  gradient.addColorStop(1, "#2D53AE");
  ctx.fillStyle = gradient;
  ctx.fillRect(0, 0, canvas.width, canvas.height);

  bubbles = bubbles.filter(bubble => bubble.active);
  bubbles.forEach(bubble => bubble.update());

  window.requestAnimationFrame(canvasDraw);
}
