#!/bin/bash

echo "Creating a static-only build for Netlify..."

# Start fresh with a new public directory
mkdir -p public_new

# Copy our static HTML file as index.html
cp public/static-preview.html public_new/index.html

# Copy existing static assets if they exist
if [ -d "public/build" ]; then
  cp -r public/build public_new/
fi

if [ -d "public/images" ]; then
  cp -r public/images public_new/
fi

if [ -d "public/favicon.ico" ]; then
  cp public/favicon.ico public_new/
fi

if [ -d "public/robots.txt" ]; then
  cp public/robots.txt public_new/
fi

# Copy redirects file for Netlify
cp public/_redirects public_new/

# Move public_new to public
rm -rf public_old
mv public public_old
mv public_new public

echo "Static site preparation complete" 