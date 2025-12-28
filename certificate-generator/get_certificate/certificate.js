const canvas = document.getElementById("certificateCanvas");
const ctx = canvas.getContext("2d");
const generateBtn = document.getElementById("generateBtn");
const downloadBtn = document.getElementById("downloadBtn");
const eventSelector = document.getElementById("eventSelector");

function wrapText(ctx, text, maxWidth) {
    const words = text.split(' ');
    const lines = [];
    let line = words[0];

    for(let i=1; i<words.length; i++){
        const word = words[i];
        const width = ctx.measureText(line + " " + word).width;
        if(width < maxWidth){
            line += " " + word;
        } else {
            lines.push(line);
            line = word;
        }
    }
    lines.push(line);
    return lines;
}

generateBtn.addEventListener("click", function(){
    const index = eventSelector.value;
    const event = matchedEvents[index];

    const img = new Image();
    img.onload = function(){
        ctx.clearRect(0,0,canvas.width,canvas.height);
        ctx.drawImage(img, 0,0,canvas.width,canvas.height);

        ctx.textAlign = "center";
        ctx.fillStyle = "#4a148c";

        ctx.font = "32px Georgia";
        ctx.fillText("Certificate of Participation", canvas.width/2, 120);

        ctx.font = "20px Arial";
        ctx.fillText("This certificate is proudly presented to", canvas.width/2, 180);

        ctx.font = "bold 30px Arial";
        ctx.fillText(event.name, canvas.width/2, 230);

        ctx.font = "18px Arial";
        const text = `In recognition of their participation in "${event.event_name}" on ${event.event_date}.
We are pleased to recognize your involvement.`;
        const lines = wrapText(ctx, text, 800);
        let y = 300;
        lines.forEach(line => { ctx.fillText(line, canvas.width/2, y); y+=26; });

        ctx.textAlign = "right";
        ctx.font = "18px Arial";
        ctx.fillText(event.controller, canvas.width-80, canvas.height-50);

        canvas.style.display = "block";
        downloadBtn.style.display = "inline-block";
    };
    img.src = "data:image/png;base64," + event.template; // fixed base64
});

downloadBtn.addEventListener("click", function(){
    const index = eventSelector.value;
    const event = matchedEvents[index];
    const link = document.createElement("a");
    link.download = `${event.name.replace(/\s+/g,"_")}_${event.event_name.replace(/\s+/g,"_")}_certificate.png`;
    link.href = canvas.toDataURL("image/png");
    link.click();
});
