docker stop php
docker rm php
docker rm mysql
foreach ($image in $(docker image ls -q)) {
    docker image rm $image --force
  }
 Write-Host "Script completed successfully."
docker image list
docker ps -a
docker compose up