<?php
// Importing all the models
$models = array_diff(scandir('./models/'), array('..', '.'));
?>

<html>
<head>
	<script src="../three.min.js"></script>
	<script src='https://cdn.jsdelivr.net/gh/mrdoob/Three.js@r92/examples/js/loaders/GLTFLoader.js'></script>
	<style type="text/css">
		body{
			margin: 0;
			background-image: linear-gradient(#bdc3c7, #2c3e50);
		}
	</style>
</head>
<body>
	<script type="text/javascript">
		// Placing the models and animating them
		function random(min, max) {
			min = Math.ceil(min);
			max = Math.floor(max);
			return Math.floor(Math.random() * (max - min + 1) + min); 
		}

		function randomfloat(min, max) {
			return Math.random() * (max - min) + min;
		}

		var models = <?php echo json_encode($models); ?>;

		let toys = [];
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
		var loader = new THREE.GLTFLoader();

		for(let [key, value] of Object.entries(models)){
			loader.load('./models/'+value, function ( gltf ) {
				gltf.scene.position.set(randomfloat(-15,15),randomfloat(10,30),0);
				gltf.scene.traverse( function ( child ) {
					if ( child.isMesh ) {
						child.material.emissive =  child.material.color;
						child.material.emissiveMap = child.material.map ;
					}
				});
				scene.add( gltf.scene );
				toys.push(gltf.scene);

			}, undefined, function ( error ) {
					//console.warn( error );
				});
		}

		camera.position.z = 10;

		const light = new THREE.AmbientLight( 0xf0f0f0, 1);
		scene.add( light );
		const animate = function () {
			requestAnimationFrame( animate );
			let i=0;
			for(let a of toys){
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
				a.position.y -= 0.02;
				/*a.position.addScaledVector(direction, speed * delta);*/
				if (a.position.y <= -10) {
					a.position.set(randomfloat(-15,15),randomfloat(10,30),0);
				} else {
				}

			}
			renderer.render( scene, camera );
		};

		animate();
	</script>
</body>
</html>