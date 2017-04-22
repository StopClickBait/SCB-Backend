#!/usr/bin/env bash
# Bootstrap Puppet on Debian Jessie
# Tested on Debian 8 64bit

set -e

PUPPETLABS_RELEASE_DEB="https://apt.puppetlabs.com/puppetlabs-release-pc1-jessie.deb"
DEB_FILE="puppet.deb"

if [ "${EUID}" -ne "0" ]; then
  /bin/echo "This script must be run as root." >&2
  exit 1
elif /usr/bin/which puppet > /dev/null 2>&1; then
  /bin/echo "Puppet is already installed."
  exit 0
fi

# Install Puppet Labs repo

/bin/echo "Configuring Puppet Labs repo..."
/usr/bin/wget --quiet "${PUPPETLABS_RELEASE_DEB}" -qO "${DEB_FILE}"
/usr/bin/dpkg -i "${DEB_FILE}"

# Update the apt repo data
/usr/bin/apt update

# Install Puppet
/bin/echo "Installing Puppet Agent..."
/usr/bin/apt install -y puppet-agent

#remove downloaded deb file
/usr/bin/unlink "${DEB_FILE}"

/bin/echo "Puppet installed!"
