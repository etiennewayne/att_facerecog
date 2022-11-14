const imageUpload = document.getElementById('imageUpload');

Promise.all([
    faceapi.nets.faceRecognitionNet.loadFromUri('/js/face/weights'),
    faceapi.nets.faceLandmark68Net.loadFromUri('/js/face/weights'),
    //faceapi.nets.ssdMobilenetv1.loadFromUri('/js/face/weights')
]).then(start);


async function start(){
    //const container = document.createElement('div');

    const container = document.getElementById('picture-container');
    //container.style.position = 'relative';
    //document.body.append(container);

    const labeledFaceDescriptors = await loadLabeledImages();
    
    const faceMatcher = new faceapi.FaceMatcher(labeledFaceDescriptors, 0.6); //ikaw ang hinungdan mabuang ko!!!!!
    document.body.append('Loaded');

    let canvas;
    let image;

    imageUpload.addEventListener('change', async () => {
        if (image) image.remove();
        if (canvas) canvas.remove();

        image = await faceapi.bufferToImage(imageUpload.files[0]); //this will get the image uploaded and can be pass now to api
        container.append(image)

        canvas = faceapi.createCanvasFromMedia(image);
        container.append(canvas);
        
        const displaySize = { width: image.width, height: image.height };
        faceapi.matchDimensions(canvas, displaySize);
        
        const detections = await faceapi.detectAllFaces(image)
            .withFaceLandmarks()
            .withFaceDescriptors(); //this will detect faces from image

        const resizeDetections = faceapi.resizeResults(detections, displaySize);

        const results = resizeDetections.map(d => faceMatcher.findBestMatch(d.descriptor));

        // resizeDetections.forEach(detection => {
        //     const box = detection.detection.box;
        //     const drawBox = new faceapi.draw.DrawBox(box, { label: 'Face'});
        //     drawBox.draw(canvas);
        // })

        results.forEach((result, i) => {
            const box = resizeDetections[i].detection.box;
            const drawBox = new faceapi.draw.DrawBox(box, { label: 'test' });
            drawBox.draw(canvas);
        })
    })
}

function loadLabeledImages() {
    const labels = ['leda', 'et'];

    return Promise.all(
        labels.map(async label => {
            const descriptions = [];

            for(let i = 1; i <= 3; i++){
                const img = await faceapi.fetchImage(`/labeled_images/${label}/${i}.jpg`)
                const detections = await faceapi.detectSingleFace(img)
                    .withFaceLandmarks().withFaceDescriptor();

                descriptions.push(detections.descriptor)
                //console.log('descriptions: ' + descriptions)
            }//loop

            return new faceapi.LabeledFaceDescriptors(label, descriptions)
        })
    );
}