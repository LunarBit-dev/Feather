#!/bin/bash

# FeatherPanel Node.js Compatibility Fix
# This script fixes the OpenSSL legacy provider issue with Node.js 17+

echo "🔧 FeatherPanel Node.js Compatibility Fix"
echo "=========================================="
echo ""

# Check Node.js version
NODE_VERSION=$(node --version)
echo "📋 Detected Node.js version: $NODE_VERSION"

# Extract major version number
NODE_MAJOR=$(echo $NODE_VERSION | cut -d'.' -f1 | sed 's/v//')

if [ "$NODE_MAJOR" -ge 17 ]; then
    echo "⚠️  Node.js 17+ detected - applying OpenSSL legacy provider fix"
    
    # Set the environment variable
    export NODE_OPTIONS="--openssl-legacy-provider"
    
    echo "🎨 Compiling assets with OpenSSL legacy provider..."
    
    # Try different build methods
    if NODE_OPTIONS="--openssl-legacy-provider" npm run build; then
        echo "✅ Assets compiled successfully with legacy provider"
    elif NODE_OPTIONS="--openssl-legacy-provider" npm run dev; then
        echo "✅ Assets compiled successfully with dev build"
    elif NODE_OPTIONS="--openssl-legacy-provider --max-old-space-size=4096" npm run build; then
        echo "✅ Assets compiled successfully with increased memory"
    else
        echo "❌ Asset compilation failed even with legacy provider"
        echo ""
        echo "📝 Manual fix options:"
        echo "   1. Downgrade to Node.js 16:"
        echo "      nvm install 16 && nvm use 16"
        echo ""
        echo "   2. Or run commands manually:"
        echo "      export NODE_OPTIONS=\"--openssl-legacy-provider\""
        echo "      npm run build"
        echo ""
        echo "   3. Or use yarn instead of npm:"
        echo "      yarn build"
        exit 1
    fi
else
    echo "✅ Node.js version is compatible - no fixes needed"
    echo "🎨 Running normal build process..."
    npm run build
fi

echo ""
echo "🎉 Build process completed!"
echo ""
echo "💡 To avoid this issue in the future, you can:"
echo "   - Add 'export NODE_OPTIONS=\"--openssl-legacy-provider\"' to your ~/.bashrc"
echo "   - Or use Node.js 16 with nvm: 'nvm use 16'"
echo "   - Or use the updated package.json scripts that include the fix"
