.PHONY: build dev
default: build

node_modules: package.json
	npm install

clean: node_modules
	gulp clean

dev: node_modules
	gulp dev

build: node_modules
	gulp build
