#!/bin/sh


# Global
sudo chown -R rd:rd ..
sudo find .. \( -type d -exec chmod 755 {} \; \) -o \( -type f -exec chmod 644 {} \; \)

