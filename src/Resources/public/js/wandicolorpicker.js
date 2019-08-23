let pickr = null;
if (pickr) {
    pickr.destroyAndRemove();
}
pickr = new Pickr(Object.assign({}, JSON.parse(document.getElementById(wandiPickrId).getAttribute("data-options")))).on('change', (color, instance) => {
    let hexa = color.toHEXA().toString();
    if (hexa.length == 7){
        hexa += 'FF';
    }
    let colorRgba = color.toRGBA();
    document.getElementById(wandiPickrId).value = hexa;
    document.getElementById(wandiPickrId).style.backgroundColor = hexa;
    document.getElementById(wandiPickrId).style.color = (Math.sqrt(
        0.299 * (colorRgba[0] * colorRgba[0]) +
        0.587 * (colorRgba[1] * colorRgba[1]) +
        0.114 * (colorRgba[2] * colorRgba[2])
    ) <= 127.5 && colorRgba[3] > 0.4) ?  '#FFF' : '#000';
});