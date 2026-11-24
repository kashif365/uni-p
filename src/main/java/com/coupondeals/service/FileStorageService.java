package com.coupondeals.service;

import org.springframework.beans.factory.annotation.Value;
import org.springframework.stereotype.Service;
import org.springframework.web.multipart.MultipartFile;

import java.io.IOException;
import java.io.UncheckedIOException;
import java.nio.file.Files;
import java.nio.file.Path;
import java.util.Locale;
import java.util.Set;
import java.util.UUID;

/** Saves uploaded images to the upload folder; they are served at /uploads/{file}. */
@Service
public class FileStorageService {

    private static final Set<String> ALLOWED = Set.of("jpg", "jpeg", "png", "gif", "webp", "svg");

    private final Path dir;

    public FileStorageService(@Value("${app.upload-dir}") String uploadDir) {
        this.dir = Path.of(uploadDir).toAbsolutePath().normalize();
    }

    public static boolean isAllowedImage(MultipartFile file) {
        return ALLOWED.contains(extension(file.getOriginalFilename()));
    }

    /** Stores the file and returns its public URL path, e.g. "/uploads/3f2a....png". */
    public String store(MultipartFile file) {
        String name = UUID.randomUUID() + "." + extension(file.getOriginalFilename());
        try {
            Files.createDirectories(dir);
            file.transferTo(dir.resolve(name));
        } catch (IOException ex) {
            throw new UncheckedIOException("Could not save upload", ex);
        }
        return "/uploads/" + name;
    }

    public void delete(String publicPath) {
        if (publicPath == null || !publicPath.startsWith("/uploads/")) {
            return;
        }
        try {
            Files.deleteIfExists(dir.resolve(publicPath.substring("/uploads/".length())).normalize());
        } catch (IOException ignored) {
            // a missing file is not an error
        }
    }

    private static String extension(String filename) {
        if (filename == null || !filename.contains(".")) {
            return "";
        }
        return filename.substring(filename.lastIndexOf('.') + 1).toLowerCase(Locale.ROOT);
    }
}
