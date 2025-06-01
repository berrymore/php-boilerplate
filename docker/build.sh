usage() {
cat << EOF

Usage: build.sh -i [image] -v [version] -t [target] -a [arch]
Build a Docker Image

Parameters:
  -i: image to build. Required.
      Choose one of: $(for i in $(ls -d */); do printf "%s" "${i%%/} "; done)
  -v: version to build. Required.
  -t: targeted environment. Default: dev
  -a: targeted architecture. Default: amd64

EOF
  exit 0
}

if [ "$#" -eq 0 ]; then usage; fi

# Parameters
IMAGE="undefined"
VERSION="undefined"
TARGET="dev"
ARCH="amd64"

while getopts "i:v:t:a:h:" optname; do
  case "$optname" in
    i) IMAGE="$OPTARG";;
    v) VERSION="$OPTARG";;
    t) TARGET="$OPTARG";;
    a) ARCH="$OPTARG";;
    h) usage;;
    *) echo "Unknown error while processing options inside build.sh";;
  esac
done

IMAGE_NAME="berrymore/$IMAGE:$VERSION-$TARGET"

cd "$IMAGE/$VERSION" || exit 1

echo "====================="
echo "Building image '$IMAGE_NAME'"

docker build --build-arg ARCH=$ARCH -t "$IMAGE_NAME" -f $TARGET.Dockerfile . || {
  echo "There was an error building the image."
  exit 1
}

echo ""

if [ $? -eq 0 ]; then
cat << EOF
  $IMAGE Docker image for version $VERSION is ready:

    --> $IMAGE_NAME
EOF
else
  echo "$IMAGE Docker Image was NOT successfully created. Check the output and correct any reported problems with the docker build operation."
fi
