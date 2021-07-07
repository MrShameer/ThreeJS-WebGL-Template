
function randomfloat(min, max) {
	return Math.random() * (max - min) + min;
}
const scene = new THREE.Scene();
const camera = new THREE.PerspectiveCamera( 75, window.innerWidth / window.innerHeight, 0.1, 100000 );

const renderer = new THREE.WebGLRenderer({antialias: true, alpha: true });
renderer.setSize( window.innerWidth, window.innerHeight );
document.body.appendChild( renderer.domElement );
window.addEventListener('resize', () => {
	renderer.setSize(window.innerWidth,window.innerHeight);
	camera.aspect = window.innerWidth / window.innerHeight;
	camera.updateProjectionMatrix();
})

//creating material for the bubble
var material = new THREE.MeshStandardMaterial( { color: 0x469bef, transparent:true, opacity: 0.1 } );

//set the number of model spawn to 15
var model=[]; number=15;
var loader = new THREE.GLTFLoader();
for (let i = 0; i < number; i++) {
	//gltf loading to load the model
	loader.load('bubbles.glb', function ( gltf ) {
		model[i]=gltf.scene;

		//giving it a random scale between 0.9 and 0.2
		let scale = randomfloat(.9,.2);
		model[i].scale.set(scale, scale, scale);

		//applying material
		model[i].traverse((o) => {
			if (o.isMesh) o.material = material;
		});
		gltf.scene.position.set(randomfloat(-15,15),randomfloat(-10,-30),0);
		scene.add(model[i]);

	}, undefined, function ( error ) {
		console.warn(error);
	});
}

camera.position.z = 10;

const light = new THREE.AmbientLight( 0xf0f0f0, 1);
scene.add( light );
const animate = function () {
	requestAnimationFrame( animate );
	let i=0;
	for(let a of model){
		//moving the model up
		a.position.y += 0.02;

		//rotating bubbles to make it different
		if(i%4==0){
			a.rotation.x += 0.01;
			a.rotation.y += 0.01;
		}
		else if(i%4==1){
			a.rotation.x -= 0.01;
			a.rotation.y -= 0.01;
		}
		else if(i%4==2){
			a.rotation.x += 0.01;
			a.rotation.y -= 0.01;
		}
		else{
			a.rotation.x -= 0.01;
			a.rotation.y += 0.01;
		}
		i+=1;

		//moving it back down if it get out of the screen for looping
		if (a.position.y >= 10) {
			a.position.set(randomfloat(-15,15),randomfloat(-10,-30),0);
		} else {
		}
	}
	renderer.render( scene, camera );
};

animate();