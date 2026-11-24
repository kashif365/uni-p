package com.coupondeals.config;

import com.coupondeals.domain.User;
import com.coupondeals.repository.StoreRepository;
import com.coupondeals.repository.UserRepository;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.boot.ApplicationArguments;
import org.springframework.boot.ApplicationRunner;
import org.springframework.core.io.ClassPathResource;
import org.springframework.jdbc.datasource.init.ResourceDatabasePopulator;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.stereotype.Component;

import javax.sql.DataSource;
import java.util.List;

/**
 * Optional start-up arguments:
 * <pre>
 *   --seed                                          load the demo data (only into an empty database)
 *   --admin-email=me@uni.ac.uk --admin-password=... create (or reset) an admin account
 * </pre>
 */
@Component
public class StartupTasks implements ApplicationRunner {

    private static final Logger log = LoggerFactory.getLogger(StartupTasks.class);

    private final DataSource dataSource;
    private final StoreRepository stores;
    private final UserRepository users;
    private final PasswordEncoder encoder;

    public StartupTasks(DataSource dataSource, StoreRepository stores, UserRepository users, PasswordEncoder encoder) {
        this.dataSource = dataSource;
        this.stores = stores;
        this.users = users;
        this.encoder = encoder;
    }

    @Override
    public void run(ApplicationArguments args) {
        if (args.containsOption("seed") && stores.count() == 0) {
            new ResourceDatabasePopulator(new ClassPathResource("db/seed/demo-data.sql")).execute(dataSource);
            log.info("Demo data loaded.");
        }
        String email = option(args, "admin-email");
        String password = option(args, "admin-password");
        if (email != null && password != null) {
            if (password.length() < 8) {
                log.error("Admin not created: the password must be at least 8 characters.");
                return;
            }
            User admin = users.findByEmailIgnoreCase(email).orElseGet(User::new);
            admin.setEmail(email.toLowerCase());
            admin.setName(admin.getName() == null ? "Administrator" : admin.getName());
            admin.setPassword(encoder.encode(password));
            admin.setRole("admin");
            users.save(admin);
            log.info("Admin account ready: {}", email);
        }
    }

    private static String option(ApplicationArguments args, String name) {
        List<String> values = args.getOptionValues(name);
        return values == null || values.isEmpty() ? null : values.get(0);
    }
}
