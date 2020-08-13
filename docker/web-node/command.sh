DIRECTORY = ${PWD}/vendor

if [ ! -d "$DIRECTORY" ]; then
    npm install
fi

npm run watch
