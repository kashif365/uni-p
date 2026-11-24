package com.campusdeals.service;

import com.campusdeals.domain.Setting;
import com.campusdeals.repository.SettingRepository;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.util.LinkedHashMap;
import java.util.List;
import java.util.Map;

/** Site settings as a key/value map, cached in memory and refreshed after every save. */
@Service
public class SettingsService {

    private static final Map<String, String> DEFAULTS = Map.of(
            "site_name", "Campus Deals",
            "site_tagline", "Save More, Shop Smart",
            "home_heading", "Save Big with Exclusive Coupon Codes",
            "home_subheading", "Discover verified student discount codes from your favourite campus brands.",
            "primary_color", "#4a0c98",
            "contact_email", "support@example.invalid");

    private final SettingRepository repository;
    private volatile Map<String, String> cache;

    public SettingsService(SettingRepository repository) {
        this.repository = repository;
    }

    /** All settings, with defaults for missing keys. */
    public Map<String, String> all() {
        Map<String, String> current = cache;
        if (current == null) {
            current = new LinkedHashMap<>(DEFAULTS);
            for (Setting s : repository.findAll()) {
                if (s.getValue() != null && !s.getValue().isBlank()) {
                    current.put(s.getKey(), s.getValue());
                }
            }
            cache = current;
        }
        return current;
    }

    public List<Setting> editable() {
        return repository.findAllByOrderBySortOrderAsc();
    }

    @Transactional
    public void update(Map<String, String> values) {
        for (Setting s : repository.findAll()) {
            if (values.containsKey(s.getKey())) {
                s.setValue(values.get(s.getKey()).trim());
            }
        }
        cache = null;
    }
}
