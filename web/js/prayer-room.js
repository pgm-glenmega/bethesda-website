document.addEventListener("DOMContentLoaded", function () {
  const container = document.getElementById("threejs-container");
  const prayerForm = document.getElementById("prayer-form");
  const closeForm = document.getElementById("close-form");

  const scene = new THREE.Scene();
  const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
  const renderer = new THREE.WebGLRenderer({ antialias: true });
  renderer.setSize(window.innerWidth, window.innerHeight);
  container.appendChild(renderer.domElement);

  // Lighting
  const directionalLight = new THREE.DirectionalLight(0xffffff, 2);
  directionalLight.position.set(10, 15, 10);
  scene.add(directionalLight);

  const ambientLight = new THREE.AmbientLight(0xffffff, 1.2);
  scene.add(ambientLight);

  // Background
  scene.background = new THREE.Color(0xf0f0f0);

  // Controls
  const controls = new THREE.OrbitControls(camera, renderer.domElement);
  controls.enableDamping = true;

  // Loader
  const loader = new THREE.GLTFLoader();
  let candleHolder = null;

  // Floor
  loader.load("/assets/models/floor_tile.glb", (gltf) => {
    const floor = gltf.scene;
    floor.scale.set(6.95, 1, 6.75);
    floor.position.set(0, -2.5, 0);
    scene.add(floor);
  });

  // Walls
  loader.load("/assets/models/wall.glb", (gltf) => {
    const wall = gltf.scene;
    wall.scale.set(3.5, 3, 1);
    wall.position.set(-7, -2.5, -7);
    scene.add(wall);
  });

  loader.load("/assets/models/wall.glb", (gltf) => {
    const wall = gltf.scene;
    wall.scale.set(3.39, 3, 1);
    wall.position.set(-7.2, -2.5, 6.6);
    wall.rotation.y = Math.PI / 2;
    scene.add(wall);
  });

  // Altar
  loader.load("/assets/models/marble_altar.glb", (gltf) => {
    const altar = gltf.scene;
    altar.scale.set(1.5, 1.5, 1.5);
    altar.position.set(0, -1.8, -3);
    scene.add(altar);
  });

  // Chairs
  function createChair(x, z) {
    loader.load("/assets/models/chair.glb", (gltf) => {
      const chair = gltf.scene;
      chair.scale.set(0.3, 0.3, 0.3);
      chair.position.set(x, 0, 3);
      scene.add(chair);
    });
  }
  for (let i = -3; i <= 3; i += 2) {
    createChair(i, 1);
    createChair(i, 2);
  }

  // Candle Holder (click target)
  loader.load("/assets/models/candle_holder.glb", (gltf) => {
    candleHolder = gltf.scene;
    candleHolder.scale.set(0.003, 0.003, 0.003);
    candleHolder.position.set(0, -1.2, -3);
    // Ensure all submeshes are raycastable
    candleHolder.traverse((obj) => {
      if (obj.isMesh) obj.frustumCulled = false;
    });
    scene.add(candleHolder);
  });

  // Click Handling (use canvas bounds, not window)
  const raycaster = new THREE.Raycaster();
  const mouse = new THREE.Vector2();

  function onCanvasClick(event) {
    const rect = renderer.domElement.getBoundingClientRect();
    mouse.x = ((event.clientX - rect.left) / rect.width) * 2 - 1;
    mouse.y = -((event.clientY - rect.top) / rect.height) * 2 + 1;

    raycaster.setFromCamera(mouse, camera);

    if (candleHolder) {
      const intersects = raycaster.intersectObject(candleHolder, true);
      if (intersects.length > 0) {
        // Makes sure the form actually becomes visible
        prayerForm.classList.remove("hidden");
        prayerForm.classList.add("show");
      }
    }
  }

  // Only listen on the WebGL canvas
  renderer.domElement.addEventListener("click", onCanvasClick);

  // Close form
  if (closeForm) {
    closeForm.addEventListener("click", () => {
      prayerForm.classList.remove("show");
      prayerForm.classList.add("hidden");
    });
  }

  // Camera & animate
  camera.position.set(0, 3, 12);

  function animate() {
    requestAnimationFrame(animate);
    controls.update();
    renderer.render(scene, camera);
  }
  animate();

  // Resize
  window.addEventListener("resize", () => {
    const w = window.innerWidth;
    const h = window.innerHeight;
    renderer.setSize(w, h);
    camera.aspect = w / h;
    camera.updateProjectionMatrix();
  });
});
