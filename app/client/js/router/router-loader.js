const loadNavScripts = () => {
  const navDirec = `${direc}/components/navbar`;
  const scriptPaths = [
    `${navDirec}/navbar.js`,
  ];
  const body = document.body;

  scriptPaths.forEach((path) => {
    const scriptSrc = `${path}`;

    if (isLogged() && !document.querySelector(`script[src="${scriptSrc}"]`)) {
      const script = document.createElement("script");
      script.type = "text/javascript";
      script.src = scriptSrc;
      script.async = false;

      body.appendChild(script);
    }
  });
};

const loadScripts = async (scripts) => {
  const body = document.body;

  await Promise.all(
    scripts.map(async (script) => {
      const { file, loadedCallback } = script;

      if (file && !document.querySelector(`script[src="${file}"]`)) {
        return new Promise((resolve, reject) => {
          const scriptElement = document.createElement("script");

          scriptElement.type = "text/javascript";
          scriptElement.src = file;
          scriptElement.async = false;

          scriptElement.onload = () => {
            loadedCallback?.();
            resolve();
          };
          scriptElement.onerror = reject;

          body.appendChild(scriptElement);
        });
      } else {
        loadedCallback?.();
        return Promise.resolve();
      }
    })
  );
};

const loadStyles = (styles) => {
  const head = document.head;

  styles.forEach((styleFile) => {
    const link = document.createElement("link");

    link.type = "text/css";
    link.rel = "stylesheet";
    link.href = styleFile;

    head.appendChild(link);
  });
};
