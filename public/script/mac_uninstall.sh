#!/bin/bash

if [ -f /usr/local/mesh_services/meshagent/meshagent ]; then
  /usr/local/mesh_services/meshagent/meshagent -fulluninstall
fi

if [ -f /opt/tacticalmesh/meshagent ]; then
  /opt/tacticalmesh/meshagent -fulluninstall
fi

launchctl bootout system /Library/LaunchDaemons/faveoagent.plist
rm -rf /usr/local/mesh_services
rm -rf /opt/tacticalmesh
rm -f /etc/faveoagent
rm -rf /opt/faveoagent
rm -f /Library/LaunchDaemons/faveoagent.plist
