## GitHub Copilot Chat

- Extension Version: 0.26.4 (prod)
- VS Code: vscode/1.99.2
- OS: Windows

## Network

User Settings:
```json
  "github.copilot.advanced.debug.useElectronFetcher": true,
  "github.copilot.advanced.debug.useNodeFetcher": false,
  "github.copilot.advanced.debug.useNodeFetchFetcher": true
```

Connecting to https://api.github.com:
- DNS ipv4 Lookup: 20.205.243.168 (41 ms)
- DNS ipv6 Lookup: Error (150 ms): getaddrinfo ENOTFOUND api.github.com
- Proxy URL: None (19 ms)
- Electron fetch (configured): HTTP 403 (267 ms)
- Node.js https: HTTP 403 (298 ms)
- Node.js fetch: HTTP 403 (208 ms)
- Helix fetch: HTTP 403 (283 ms)

Connecting to https://api.individual.githubcopilot.com/_ping:
- DNS ipv4 Lookup: 140.82.112.21 (4 ms)
- DNS ipv6 Lookup: Error (78 ms): getaddrinfo ENOTFOUND api.individual.githubcopilot.com
- Proxy URL: None (14 ms)
- Electron fetch (configured): Error (733 ms): Error: net::ERR_CERT_DATE_INVALID
    at SimpleURLLoaderWrapper.<anonymous> (node:electron/js2c/utility_init:2:10511)
    at SimpleURLLoaderWrapper.emit (node:events:518:28)
- Node.js https: Error (861 ms): Error: certificate is not yet valid
    at TLSSocket.onConnectSecure (node:_tls_wrap:1677:34)
    at TLSSocket.emit (node:events:518:28)
    at TLSSocket._finishInit (node:_tls_wrap:1076:8)
    at TLSWrap.ssl.onhandshakedone (node:_tls_wrap:862:12)
- Node.js fetch: Error (656 ms): TypeError: fetch failed
    at node:internal/deps/undici/undici:13502:13
    at processTicksAndRejections (node:internal/process/task_queues:95:5)
    at hM._fetch (c:\Users\ASUS\.vscode\extensions\github.copilot-chat-0.26.4\dist\extension.js:778:25942)
    at c:\Users\ASUS\.vscode\extensions\github.copilot-chat-0.26.4\dist\extension.js:809:134
    at vw.h (file:///c:/Users/ASUS/AppData/Local/Programs/Microsoft%20VS%20Code/resources/app/out/vs/workbench/api/node/extensionHostProcess.js:111:41495)
  Error: certificate is not yet valid
      at TLSSocket.onConnectSecure (node:_tls_wrap:1677:34)
      at TLSSocket.emit (node:events:518:28)
      at TLSSocket._finishInit (node:_tls_wrap:1076:8)
      at TLSWrap.ssl.onhandshakedone (node:_tls_wrap:862:12)
- Helix fetch: Error (665 ms): FetchError: certificate is not yet valid
    at rut (c:\Users\ASUS\.vscode\extensions\github.copilot-chat-0.26.4\dist\extension.js:304:29579)
    at processTicksAndRejections (node:internal/process/task_queues:95:5)
    at qSr (c:\Users\ASUS\.vscode\extensions\github.copilot-chat-0.26.4\dist\extension.js:304:31605)
    at mS.fetch (c:\Users\ASUS\.vscode\extensions\github.copilot-chat-0.26.4\dist\extension.js:779:2495)
    at c:\Users\ASUS\.vscode\extensions\github.copilot-chat-0.26.4\dist\extension.js:809:134
    at vw.h (file:///c:/Users/ASUS/AppData/Local/Programs/Microsoft%20VS%20Code/resources/app/out/vs/workbench/api/node/extensionHostProcess.js:111:41495)

## Documentation

In corporate networks: [Troubleshooting firewall settings for GitHub Copilot](https://docs.github.com/en/copilot/troubleshooting-github-copilot/troubleshooting-firewall-settings-for-github-copilot).