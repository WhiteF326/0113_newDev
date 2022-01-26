 <!--
    周辺デバイスの検知、UUIDでフィルタリングして一つだけ表示する
-->

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<script>
/*navigator.bluetooth.requestDevice({
  filters: [{
    services: ['heart_rate'],
  }]}) 
  .catch(error => console.log(error));
*/

function startDeviceScanner() {
    const SCAN_OPTIONS = {
    acceptAllAdvertisements: true,
    keepRepeatedDevices: true
    };
    console.log(navigator);
    console.log(navigator.bluetooth);

    navigator.bluetooth.requestDevice({acceptAllDevices: true})
        .then(scanner => {

            console.log(scanner.active);

            navigator.bluetooth.addEventListener('advertisementreceived', event => {

                /* Display device data */
                let deviceData = event.device;

                if (document.getElementById(deviceData.id)) {
                    //update the device data displayed
                    updataDeviceData(deviceData);

                } else {
                    //insert device data
                    insertDeviceData(deviceData);
                }
            });

    })
        .catch(error => { console.log(error); });

}

function SearchDevice() {
    navigator.bluetooth.requestDevice({ acceptAllDevices: true });
}

async function onStartButtonClick() {
        await navigator.bluetooth.requestLEScan({
          filters: [{ services: ["cba20d00-224d-11e6-9fb8-0002a5d5c51b"] }],
          keepRepeatedDevices: true,
        });
      }

</script>
<input type="button" value="自分のデバイスを探す" onclick="startDeviceScanner();">   
</body>
</html>